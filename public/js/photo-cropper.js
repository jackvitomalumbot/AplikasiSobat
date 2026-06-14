/**
 * SobatMedis Photo Cropper — Vanilla JS
 * Allows users to pan, zoom, and crop profile photos to a circular frame.
 * Outputs a cropped 400x400 JPEG blob attached to the form.
 */
(function () {
    'use strict';

    class PhotoCropper {
        constructor(options) {
            this.inputId = options.inputId;           // original file input id
            this.formId = options.formId;             // form id to attach cropped file
            this.previewImg = options.previewImg;     // avatar img element
            this.cropSize = options.cropSize || 220;  // circle guide diameter
            this.outputSize = options.outputSize || 400; // output px

            this.modal = null;
            this.canvas = null;
            this.ctx = null;
            this.img = null;

            // Transform state
            this.scale = 1;
            this.minScale = 0.5;
            this.maxScale = 3;
            this.offsetX = 0;
            this.offsetY = 0;
            this.dragging = false;
            this.dragStartX = 0;
            this.dragStartY = 0;
            this.lastOffsetX = 0;
            this.lastOffsetY = 0;

            this.init();
        }

        init() {
            this.buildModal();
            this.bindInput();
        }

        buildModal() {
            // Create overlay
            const overlay = document.createElement('div');
            overlay.className = 'cropper-modal-overlay';
            overlay.innerHTML = `
                <div class="cropper-modal">
                    <div class="cropper-modal-header">
                        <h3>Atur Foto Profile</h3>
                        <button class="cropper-modal-close" type="button" aria-label="Close">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                    <div class="cropper-container-wrapper">
                        <img class="cropper-image" src="" alt="Crop preview">
                        <div class="cropper-guide"></div>
                    </div>
                    <div class="cropper-controls">
                        <div class="cropper-zoom-row">
                            <label>Zoom</label>
                            <input type="range" class="cropper-zoom-slider" min="50" max="300" value="100" step="1">
                            <span class="cropper-zoom-value" style="font-size:13px;min-width:36px;text-align:right;">100%</span>
                        </div>
                    </div>
                    <div class="cropper-actions">
                        <button type="button" class="btn btn-outline cropper-btn-cancel">Batal</button>
                        <button type="button" class="btn btn-primary cropper-btn-save">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Simpan Foto
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
            this.modal = overlay;

            // References
            this.cropperImg = overlay.querySelector('.cropper-image');
            this.wrapper = overlay.querySelector('.cropper-container-wrapper');
            this.slider = overlay.querySelector('.cropper-zoom-slider');
            this.zoomLabel = overlay.querySelector('.cropper-zoom-value');

            // Events
            overlay.querySelector('.cropper-modal-close').addEventListener('click', () => this.close());
            overlay.querySelector('.cropper-btn-cancel').addEventListener('click', () => this.close());
            overlay.querySelector('.cropper-btn-save').addEventListener('click', () => this.save());
            overlay.addEventListener('click', (e) => { if (e.target === overlay) this.close(); });

            // Zoom slider
            this.slider.addEventListener('input', (e) => {
                this.scale = parseInt(e.target.value) / 100;
                this.zoomLabel.textContent = e.target.value + '%';
                this.applyTransform();
            });

            // Pan — mouse
            this.wrapper.addEventListener('mousedown', (e) => this.onDragStart(e));
            document.addEventListener('mousemove', (e) => this.onDragMove(e));
            document.addEventListener('mouseup', () => this.onDragEnd());

            // Pan — touch
            this.wrapper.addEventListener('touchstart', (e) => this.onDragStart(e), { passive: false });
            document.addEventListener('touchmove', (e) => this.onDragMove(e), { passive: false });
            document.addEventListener('touchend', () => this.onDragEnd());

            // Zoom — mouse wheel
            this.wrapper.addEventListener('wheel', (e) => {
                e.preventDefault();
                const delta = e.deltaY > 0 ? -0.05 : 0.05;
                this.scale = Math.max(this.minScale, Math.min(this.maxScale, this.scale + delta));
                this.slider.value = Math.round(this.scale * 100);
                this.zoomLabel.textContent = Math.round(this.scale * 100) + '%';
                this.applyTransform();
            }, { passive: false });
        }

        bindInput() {
            const input = document.getElementById(this.inputId);
            if (!input) return;

            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file || !file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = (ev) => {
                    this.loadImage(ev.target.result);
                };
                reader.readAsDataURL(file);
            });
        }

        loadImage(src) {
            const img = new Image();
            img.onload = () => {
                this.img = img;

                const wrapW = this.wrapper.offsetWidth;
                const wrapH = this.wrapper.offsetHeight;

                // Fit image so smallest side fills crop circle
                const imgRatio = img.width / img.height;
                let displayW, displayH;
                if (imgRatio > 1) {
                    displayH = this.cropSize;
                    displayW = displayH * imgRatio;
                } else {
                    displayW = this.cropSize;
                    displayH = displayW / imgRatio;
                }

                this.baseWidth = displayW;
                this.baseHeight = displayH;
                this.scale = 1;
                this.offsetX = 0;
                this.offsetY = 0;
                this.slider.value = 100;
                this.zoomLabel.textContent = '100%';

                this.cropperImg.src = src;
                this.applyTransform();
                this.open();
            };
            img.src = src;
        }

        applyTransform() {
            const w = this.baseWidth * this.scale;
            const h = this.baseHeight * this.scale;
            const wrapW = this.wrapper.offsetWidth;
            const wrapH = this.wrapper.offsetHeight;

            this.cropperImg.style.width = w + 'px';
            this.cropperImg.style.height = h + 'px';
            this.cropperImg.style.left = ((wrapW - w) / 2 + this.offsetX) + 'px';
            this.cropperImg.style.top = ((wrapH - h) / 2 + this.offsetY) + 'px';
        }

        onDragStart(e) {
            e.preventDefault();
            this.dragging = true;
            this.cropperImg.classList.add('grabbing');
            const point = e.touches ? e.touches[0] : e;
            this.dragStartX = point.clientX;
            this.dragStartY = point.clientY;
            this.lastOffsetX = this.offsetX;
            this.lastOffsetY = this.offsetY;
        }

        onDragMove(e) {
            if (!this.dragging) return;
            e.preventDefault();
            const point = e.touches ? e.touches[0] : e;
            this.offsetX = this.lastOffsetX + (point.clientX - this.dragStartX);
            this.offsetY = this.lastOffsetY + (point.clientY - this.dragStartY);
            this.applyTransform();
        }

        onDragEnd() {
            this.dragging = false;
            this.cropperImg.classList.remove('grabbing');
        }

        open() {
            this.modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        close() {
            this.modal.classList.remove('active');
            document.body.style.overflow = '';
            // Reset the file input
            const input = document.getElementById(this.inputId);
            if (input) input.value = '';
        }

        save() {
            if (!this.img) return;

            const canvas = document.createElement('canvas');
            canvas.width = this.outputSize;
            canvas.height = this.outputSize;
            const ctx = canvas.getContext('2d');

            // Calculate crop area
            const wrapW = this.wrapper.offsetWidth;
            const wrapH = this.wrapper.offsetHeight;
            const scaledW = this.baseWidth * this.scale;
            const scaledH = this.baseHeight * this.scale;

            // Image position on screen
            const imgScreenX = (wrapW - scaledW) / 2 + this.offsetX;
            const imgScreenY = (wrapH - scaledH) / 2 + this.offsetY;

            // Crop circle position on screen (centered)
            const circleX = (wrapW - this.cropSize) / 2;
            const circleY = (wrapH - this.cropSize) / 2;

            // Source coordinates in original image pixels
            const scaleRatio = this.img.width / scaledW;
            const sx = (circleX - imgScreenX) * scaleRatio;
            const sy = (circleY - imgScreenY) * scaleRatio;
            const sSize = this.cropSize * scaleRatio;

            // Draw circular crop
            ctx.beginPath();
            ctx.arc(this.outputSize / 2, this.outputSize / 2, this.outputSize / 2, 0, Math.PI * 2);
            ctx.closePath();
            ctx.clip();

            ctx.drawImage(this.img, sx, sy, sSize, sSize, 0, 0, this.outputSize, this.outputSize);

            // Convert to blob and attach to form
            canvas.toBlob((blob) => {
                if (!blob) return;

                // Create a new File from blob
                const croppedFile = new File([blob], 'profile_cropped.jpg', { type: 'image/jpeg' });

                // Create a new DataTransfer to replace the file input value
                const dt = new DataTransfer();
                dt.items.add(croppedFile);
                const input = document.getElementById(this.inputId);
                if (input) {
                    input.files = dt.files;
                }

                // Update avatar preview
                if (this.previewImg) {
                    this.previewImg.src = URL.createObjectURL(blob);
                }

                this.close();
            }, 'image/jpeg', 0.92);
        }
    }

    // Auto-init: look for elements with data-photo-cropper
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-photo-cropper]').forEach(el => {
            new PhotoCropper({
                inputId: el.dataset.inputId || 'foto_profile',
                formId: el.dataset.formId || '',
                previewImg: el.querySelector('.avatar'),
                cropSize: parseInt(el.dataset.cropSize) || 220,
                outputSize: parseInt(el.dataset.outputSize) || 400,
            });
        });
    });

    window.PhotoCropper = PhotoCropper;
})();

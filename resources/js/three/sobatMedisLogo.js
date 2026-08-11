/**
 * SobatMedis — 3D Logo Hero (Three.js)
 * Premium medallion effect with subtle floating animation and mouse tracking.
 * resources/js/three/sobatMedisLogo.js
 */
import * as THREE from 'three';

export function initSobatMedisLogo(canvasId, logoUrl) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return null;

    /* ── Renderer ── */
    const renderer = new THREE.WebGLRenderer({
        canvas,
        antialias: true,
        alpha: true,
    });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0);
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    renderer.outputColorSpace = THREE.SRGBColorSpace;

    /* ── Scene ── */
    const scene = new THREE.Scene();

    /* ── Camera ── */
    const camera = new THREE.PerspectiveCamera(40, 1, 0.1, 100);
    camera.position.set(0, 0, 5);

    /* ── Lights ── */
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.2);
    scene.add(ambientLight);

    const keyLight = new THREE.DirectionalLight(0xfff8f0, 1.8);
    keyLight.position.set(3, 4, 5);
    keyLight.castShadow = true;
    keyLight.shadow.mapSize.width  = 512;
    keyLight.shadow.mapSize.height = 512;
    keyLight.shadow.camera.near = 0.1;
    keyLight.shadow.camera.far  = 20;
    scene.add(keyLight);

    const fillLight = new THREE.DirectionalLight(0xe8f4fd, 0.6);
    fillLight.position.set(-3, 2, 3);
    scene.add(fillLight);

    const rimLight = new THREE.DirectionalLight(0xfff0e0, 0.4);
    rimLight.position.set(0, -3, -2);
    scene.add(rimLight);

    /* ── Texture Loader ── */
    const loader = new THREE.TextureLoader();

    /* ── Medallion Geometry ── */
    // Circular disc with subtle extrusion depth via CylinderGeometry
    const SEGMENTS = 128;
    const RADIUS   = 1.8;
    const DEPTH    = 0.22;

    const bodyGeo  = new THREE.CylinderGeometry(RADIUS, RADIUS, DEPTH, SEGMENTS, 1, false);
    const rimGeo   = new THREE.TorusGeometry(RADIUS, 0.045, 16, SEGMENTS);

    // Logo face material (cream/off-white, receives texture)
    const faceMat = new THREE.MeshStandardMaterial({
        color: 0xede8dc,
        roughness: 0.55,
        metalness: 0.08,
    });

    // Edge (side) of medallion — slightly darker cream
    const edgeMat = new THREE.MeshStandardMaterial({
        color: 0xd8d0c0,
        roughness: 0.6,
        metalness: 0.05,
    });

    // Rim ring — dark like logo border
    const rimMat = new THREE.MeshStandardMaterial({
        color: 0x1a1a1a,
        roughness: 0.4,
        metalness: 0.15,
    });

    // Cylinder uses two materials: top=faceMat, side=edgeMat, bottom=faceMat
    const medallion = new THREE.Mesh(bodyGeo, [faceMat, edgeMat, faceMat]);
    medallion.rotation.x = Math.PI / 2; // face toward camera
    medallion.castShadow = true;
    medallion.receiveShadow = true;
    scene.add(medallion);

    const rim = new THREE.Mesh(rimGeo, rimMat);
    rim.castShadow = true;
    scene.add(rim);

    /* ── Logo Texture (applied after load) ── */
    loader.load(
        logoUrl,
        (texture) => {
            texture.colorSpace = THREE.SRGBColorSpace;
            texture.anisotropy = renderer.capabilities.getMaxAnisotropy();

            // Plane on top of medallion face displaying the logo
            const logoAspect = 1; // logo is circular, 1:1
            const planeGeo  = new THREE.PlaneGeometry(
                RADIUS * 2 * 0.95,
                RADIUS * 2 * 0.95 * logoAspect
            );
            const planeMat  = new THREE.MeshStandardMaterial({
                map: texture,
                transparent: true,
                roughness: 0.5,
                metalness: 0.0,
                depthWrite: false,
            });
            const logoPlane = new THREE.Mesh(planeGeo, planeMat);
            logoPlane.position.z = DEPTH / 2 + 0.001; // just in front of disc face
            medallion.add(logoPlane); // child of medallion, rotates with it
        },
        undefined,
        () => {
            // Fallback: just show the cream medallion without texture
            console.warn('[SobatMedis3D] Logo texture failed to load');
        }
    );

    /* ── Drop Shadow Plane ── */
    const shadowGeo = new THREE.CircleGeometry(RADIUS * 1.1, SEGMENTS);
    const shadowMat = new THREE.MeshBasicMaterial({
        color: 0x000000,
        transparent: true,
        opacity: 0.10,
        depthWrite: false,
    });
    const shadowPlane = new THREE.Mesh(shadowGeo, shadowMat);
    shadowPlane.rotation.x = -Math.PI / 2;
    shadowPlane.position.y = -(DEPTH / 2 + 0.55);
    scene.add(shadowPlane);

    /* ── Animation State ── */
    let floatTime = 0;
    let animationId = null;
    let isVisible = true;

    // Target and current rotation for mouse tracking
    const targetRotX = { value: 0 };
    const targetRotY = { value: 0 };
    const currentRotX = { value: 0 };
    const currentRotY = { value: 0 };

    // Float params
    const FLOAT_AMPLITUDE = 0.08; // units
    const FLOAT_SPEED     = 0.45; // rad/s — ~14s period
    const MAX_MOUSE_ROT   = 0.12; // radians (~7°)
    const LERP_MOUSE      = 0.06;

    /* ── Mouse Interaction ── */
    const isMobile = window.matchMedia('(max-width: 768px)').matches;

    function onMouseMove(e) {
        if (isMobile) return;
        const hero = canvas.closest('.hero') || document.body;
        const rect = hero.getBoundingClientRect();
        const nx = ((e.clientX - rect.left) / rect.width)  * 2 - 1; // -1 to +1
        const ny = ((e.clientY - rect.top)  / rect.height) * 2 - 1;
        targetRotY.value =  nx * MAX_MOUSE_ROT;
        targetRotX.value = -ny * MAX_MOUSE_ROT * 0.6;
    }

    function onMouseLeave() {
        targetRotX.value = 0;
        targetRotY.value = 0;
    }

    const heroEl = canvas.closest('.hero') || document.documentElement;
    heroEl.addEventListener('mousemove', onMouseMove, { passive: true });
    heroEl.addEventListener('mouseleave', onMouseLeave, { passive: true });

    /* ── Resize ── */
    function resize() {
        const parent = canvas.parentElement;
        if (!parent) return;
        const w = parent.clientWidth;
        const h = parent.clientHeight;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    }
    resize();
    const ro = new ResizeObserver(resize);
    ro.observe(canvas.parentElement);

    /* ── Entry Animation ── */
    let entryProgress = 0;
    const ENTRY_DURATION = 1.0; // seconds
    let lastTime = null;

    /* ── Render Loop ── */
    function animate(ts) {
        animationId = requestAnimationFrame(animate);
        if (!isVisible) return;

        const dt = lastTime ? Math.min((ts - lastTime) / 1000, 0.05) : 0.016;
        lastTime = ts;

        // Entry fade-in
        entryProgress = Math.min(entryProgress + dt / ENTRY_DURATION, 1);
        const entry = easeOutCubic(entryProgress);

        // Float
        floatTime += dt * FLOAT_SPEED;
        const floatY = Math.sin(floatTime) * FLOAT_AMPLITUDE;

        // Subtle idle rotation in float
        const idleRotZ = Math.sin(floatTime * 0.7) * 0.015;
        const idleRotX = Math.cos(floatTime * 0.5) * 0.012;

        // Mouse lerp
        currentRotX.value += (targetRotX.value - currentRotX.value) * LERP_MOUSE;
        currentRotY.value += (targetRotY.value - currentRotY.value) * LERP_MOUSE;

        // Apply to medallion
        medallion.position.y  = floatY * entry;
        rim.position.y        = floatY * entry;
        shadowPlane.position.y = -(DEPTH / 2 + 0.55 + floatY * 0.3 * entry);
        shadowMat.opacity     = (0.10 - Math.abs(floatY) * 0.02) * entry;

        // Combined rotation: idle subtle + mouse
        medallion.rotation.x = Math.PI / 2 + idleRotX + currentRotX.value;
        medallion.rotation.z = idleRotZ + currentRotY.value; // z acts as Y for a disc
        rim.rotation.x = idleRotX + currentRotX.value;
        rim.rotation.z = idleRotZ + currentRotY.value;

        // Scale entry
        const entryScale = 0.6 + 0.4 * entry;
        medallion.scale.setScalar(entryScale);
        rim.scale.setScalar(entryScale);

        // Fade shadow plane with entry
        shadowMat.opacity = 0.10 * entry;

        renderer.render(scene, camera);
    }

    function easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
    }

    /* ── Visibility API ── */
    function onVisibility() {
        isVisible = !document.hidden;
        if (isVisible && !animationId) {
            lastTime = null;
            animate(performance.now());
        }
    }
    document.addEventListener('visibilitychange', onVisibility);

    // Start
    animate(performance.now());

    /* ── Cleanup ── */
    function destroy() {
        cancelAnimationFrame(animationId);
        animationId = null;
        ro.disconnect();
        heroEl.removeEventListener('mousemove', onMouseMove);
        heroEl.removeEventListener('mouseleave', onMouseLeave);
        document.removeEventListener('visibilitychange', onVisibility);
        renderer.dispose();
        bodyGeo.dispose();
        rimGeo.dispose();
        shadowGeo.dispose();
        faceMat.dispose();
        edgeMat.dispose();
        rimMat.dispose();
        rimMat.dispose();
        shadowMat.dispose();
    }

    return { destroy };
}

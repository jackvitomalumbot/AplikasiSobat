/**
 * SobatMedis — 3D Logo Hero (Three.js)
 * Clean group-based approach: CircleGeometry faces + open CylinderGeometry side.
 * Logo plane sits at z = DEPTH/2 + ε, directly facing the camera.
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
    renderer.shadowMap.enabled = false; // perf: off for simple scene
    renderer.outputColorSpace = THREE.SRGBColorSpace;

    /* ── Scene & Camera ── */
    const scene  = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(38, 1, 0.1, 100);
    camera.position.set(0, 0, 5.5);

    /* ── Lights ── */
    // Ambient — fills all surfaces evenly
    scene.add(new THREE.AmbientLight(0xffffff, 1.4));

    // Key light — top-right-front
    const keyLight = new THREE.DirectionalLight(0xfff8f0, 1.6);
    keyLight.position.set(3, 5, 6);
    scene.add(keyLight);

    // Fill light — left
    const fillLight = new THREE.DirectionalLight(0xe8f4fd, 0.5);
    fillLight.position.set(-4, 2, 4);
    scene.add(fillLight);

    // Rim light — bottom, adds depth to edge
    const rimLight = new THREE.DirectionalLight(0xfff0e0, 0.35);
    rimLight.position.set(0, -4, -2);
    scene.add(rimLight);

    /* ── Geometry constants ── */
    const SEG    = 128;      // circle/cylinder segments — smooth edge
    const RADIUS = 1.85;     // logo circle radius (world units)
    const DEPTH  = 0.20;     // medallion thickness

    /* ── Materials ── */
    const faceMat = new THREE.MeshStandardMaterial({
        color: 0xede8dc,   // cream — matches logo background
        roughness: 0.5,
        metalness: 0.06,
    });

    const edgeMat = new THREE.MeshStandardMaterial({
        color: 0xccc5b0,   // slightly darker cream for side
        roughness: 0.6,
        metalness: 0.04,
    });

    const rimMat = new THREE.MeshStandardMaterial({
        color: 0x1a1a1a,   // near-black — matches logo border
        roughness: 0.35,
        metalness: 0.2,
    });

    /* ── Build medallion Group ── */
    const group = new THREE.Group();
    scene.add(group);

    // 1. Front face — CircleGeometry, naturally faces +Z (toward camera)
    const frontGeo  = new THREE.CircleGeometry(RADIUS, SEG);
    const frontMesh = new THREE.Mesh(frontGeo, faceMat);
    frontMesh.position.z = DEPTH / 2;
    group.add(frontMesh);

    // 2. Back face — flip 180° so it faces -Z (away from camera)
    const backGeo  = new THREE.CircleGeometry(RADIUS, SEG);
    const backMesh = new THREE.Mesh(backGeo, faceMat);
    backMesh.rotation.y  = Math.PI;
    backMesh.position.z  = -(DEPTH / 2);
    group.add(backMesh);

    // 3. Side cylinder — open top & bottom (just the lateral surface)
    const sideGeo = new THREE.CylinderGeometry(RADIUS, RADIUS, DEPTH, SEG, 1, true);
    sideGeo.applyMatrix4(new THREE.Matrix4().makeRotationX(Math.PI / 2)); // bake rotation into geometry
    const sideMesh = new THREE.Mesh(sideGeo, edgeMat);
    group.add(sideMesh);

    // 4. Rim ring — black border matching logo
    const rimGeo  = new THREE.TorusGeometry(RADIUS, 0.048, 20, SEG);
    const rimMesh = new THREE.Mesh(rimGeo, rimMat);
    group.add(rimMesh);

    // 5. Logo texture plane — sits just in front of face
    //    PlaneGeometry naturally faces +Z, so position.z = face + ε is correct
    const logoLoader = new THREE.TextureLoader();
    logoLoader.load(
        logoUrl,
        (tex) => {
            tex.colorSpace = THREE.SRGBColorSpace;
            tex.anisotropy  = Math.min(renderer.capabilities.getMaxAnisotropy(), 8);

            const logoMat  = new THREE.MeshStandardMaterial({
                map: tex,
                transparent: true,
                alphaTest: 0.01,
                roughness: 0.45,
                metalness: 0.0,
                depthWrite: false,
            });

            // Keep 1:1 aspect for the circular logo
            const d = RADIUS * 2 * 0.94;
            const logoGeo  = new THREE.PlaneGeometry(d, d);
            const logoMesh = new THREE.Mesh(logoGeo, logoMat);
            logoMesh.position.z = DEPTH / 2 + 0.003; // just above front face
            group.add(logoMesh);
        },
        undefined,
        () => { console.warn('[SobatMedis3D] Logo texture failed, showing plain medallion.'); }
    );

    /* ── Soft drop shadow (2D circle under group) ── */
    const shadowGeo = new THREE.CircleGeometry(RADIUS * 1.05, SEG);
    const shadowMat = new THREE.MeshBasicMaterial({
        color: 0x000000,
        transparent: true,
        opacity: 0.08,
        depthWrite: false,
    });
    const shadowPlane = new THREE.Mesh(shadowGeo, shadowMat);
    shadowPlane.rotation.x = -Math.PI / 2;
    shadowPlane.position.y = -(RADIUS + 0.4);
    scene.add(shadowPlane);

    /* ── Animation state ── */
    const FLOAT_AMP   = 0.09;    // float amplitude (world units)
    const FLOAT_SPEED = 0.44;    // rad/s (~14 s period)
    const MAX_MOUSE   = 0.13;    // max mouse rotation (radians, ~7.5°)
    const LERP_M      = 0.055;   // mouse lerp factor

    let floatTime  = 0;
    let entryProg  = 0;
    const ENTRY_DUR = 1.1;       // entry animation duration (seconds)

    const mouseTarget  = { x: 0, y: 0 };
    const mouseCurrent = { x: 0, y: 0 };

    let animId   = null;
    let lastTs   = null;
    let isVisible = true;

    /* ── Mouse tracking ── */
    const isMobile = window.matchMedia('(max-width: 768px)').matches;

    function onMouseMove(e) {
        if (isMobile) return;
        const hero = document.getElementById('heroSection') || document.body;
        const rect = hero.getBoundingClientRect();
        const nx =  ((e.clientX - rect.left)  / rect.width)  * 2 - 1;
        const ny = -((e.clientY - rect.top)   / rect.height) * 2 + 1;
        mouseTarget.y =  nx * MAX_MOUSE;
        mouseTarget.x =  ny * MAX_MOUSE * 0.55;
    }

    function onMouseLeave() {
        mouseTarget.x = 0;
        mouseTarget.y = 0;
    }

    const heroEl = document.getElementById('heroSection') || document.documentElement;
    heroEl.addEventListener('mousemove',  onMouseMove,  { passive: true });
    heroEl.addEventListener('mouseleave', onMouseLeave, { passive: true });

    /* ── Resize ── */
    function resize() {
        const p = canvas.parentElement;
        if (!p) return;
        const w = p.clientWidth;
        const h = p.clientHeight;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    }
    resize();
    const ro = new ResizeObserver(resize);
    ro.observe(canvas.parentElement);

    /* ── Easing ── */
    const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

    /* ── Render loop ── */
    function animate(ts) {
        animId = requestAnimationFrame(animate);
        if (!isVisible) return;

        const dt = lastTs ? Math.min((ts - lastTs) / 1000, 0.05) : 0.016;
        lastTs = ts;

        // Entry
        entryProg = Math.min(entryProg + dt / ENTRY_DUR, 1);
        const entry = easeOutCubic(entryProg);

        // Float
        floatTime += dt * FLOAT_SPEED;
        const floatY   = Math.sin(floatTime) * FLOAT_AMP;
        const idleRotX = Math.cos(floatTime * 0.6) * 0.010;
        const idleRotZ = Math.sin(floatTime * 0.4) * 0.012;

        // Mouse lerp
        mouseCurrent.x += (mouseTarget.x - mouseCurrent.x) * LERP_M;
        mouseCurrent.y += (mouseTarget.y - mouseCurrent.y) * LERP_M;

        // Apply to group
        group.position.y   = floatY * entry;
        group.rotation.x   = idleRotX + mouseCurrent.x;
        group.rotation.y   = idleRotZ + mouseCurrent.y;

        // Shadow tracking
        shadowPlane.position.y = -(RADIUS + 0.4 + floatY * 0.25 * entry);
        shadowMat.opacity      = 0.08 * entry;

        // Entry scale
        const scl = 0.65 + 0.35 * entry;
        group.scale.setScalar(scl);

        renderer.render(scene, camera);
    }

    /* ── Page visibility ── */
    function onVis() {
        isVisible = !document.hidden;
        if (isVisible && !animId) { lastTs = null; animate(performance.now()); }
    }
    document.addEventListener('visibilitychange', onVis);

    animate(performance.now());

    /* ── Cleanup ── */
    return {
        destroy() {
            cancelAnimationFrame(animId);
            animId = null;
            ro.disconnect();
            heroEl.removeEventListener('mousemove',  onMouseMove);
            heroEl.removeEventListener('mouseleave', onMouseLeave);
            document.removeEventListener('visibilitychange', onVis);
            renderer.dispose();
            [frontGeo, backGeo, sideGeo, rimGeo, shadowGeo, shadowMat, faceMat, edgeMat, rimMat].forEach(x => x.dispose());
        }
    };
}

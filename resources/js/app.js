import { initSobatMedisLogo } from './three/sobatMedisLogo.js';

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('sobatMedisLogoCanvas');
    if (canvas) {
        const logoUrl = canvas.dataset.logoUrl || '/images/logo.png';
        const instance = initSobatMedisLogo('sobatMedisLogoCanvas', logoUrl);

        // Cleanup on navigation (SPA-style, defensive)
        window.addEventListener('beforeunload', () => {
            if (instance) instance.destroy();
        });
    }
});

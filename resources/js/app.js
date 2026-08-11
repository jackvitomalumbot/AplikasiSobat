import { initSobatMedisLogo } from './three/sobatMedisLogo.js';

const instances = [];

document.addEventListener('DOMContentLoaded', () => {
    // Init setiap canvas yang punya data-logo-url
    document.querySelectorAll('canvas[data-logo-url]').forEach((canvas) => {
        const logoUrl = canvas.dataset.logoUrl || '/images/logo.png';
        const inst = initSobatMedisLogo(canvas.id, logoUrl);
        if (inst) instances.push(inst);
    });

    // Entry animation untuk info page hero
    const infoContent = document.getElementById('infoHeroContent');
    if (infoContent) infoContent.classList.add('is-visible');
    document.querySelectorAll('.info-hero-logo').forEach((el) => {
        setTimeout(() => el.classList.add('is-visible'), 150);
    });

    // Entry animation untuk homepage hero
    const heroContent = document.getElementById('heroContent');
    const heroVisual  = document.getElementById('heroVisual');
    if (heroContent) heroContent.classList.add('is-visible');
    if (heroVisual)  setTimeout(() => heroVisual.classList.add('is-visible'), 200);
});

// Cleanup
window.addEventListener('beforeunload', () => {
    instances.forEach((inst) => { if (inst) inst.destroy(); });
});

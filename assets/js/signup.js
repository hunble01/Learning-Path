// JS for particles
const particles = document.createElement("div");
particles.className = "particles";
document.body.appendChild(particles);

for (let i = 0; i < 50; i++) {
    const particle = document.createElement("div");
    particle.className = "particle";
    particles.appendChild(particle);
}

document.addEventListener("mousemove", (e) => {
    const mouseX = e.clientX / window.innerWidth - 0.5;
    const mouseY = e.clientY / window.innerHeight - 0.5;

    particles.style.transform = `translate(-50%, -50%) translate(${mouseX * 200}px, ${mouseY * 200}px)`;
});


// Particles animation
class Particle {
    constructor(canvas, ctx, mousePosition, options) {
        this.canvas = canvas;
        this.ctx = ctx;
        this.mousePosition = mousePosition;
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = Math.random() * (options.maxSize - options.minSize) + options.minSize;
        this.speedX = Math.random() * 0.5 - 0.25;
        this.speedY = Math.random() * 0.5 - 0.25;
        this.color = options.particleColor;
    }

    update() {
        this.x += this.speedX;
        this.y += this.speedY;

        if (this.x > this.canvas.width) this.x = 0;
        if (this.x < 0) this.x = this.canvas.width;
        if (this.y > this.canvas.height) this.y = 0;
        if (this.y < 0) this.y = this.canvas.height;

        // Mouse interaction
        const dx = this.mousePosition.x - this.x;
        const dy = this.mousePosition.y - this.y;
        const distance = Math.sqrt(dx * dx + dy * dy);
        if (distance < 100) {
            const angle = Math.atan2(dy, dx);
            this.x -= Math.cos(angle) * 1;
            this.y -= Math.sin(angle) * 1;
        }
    }

    draw() {
        this.ctx.fillStyle = this.color;
        this.ctx.beginPath();
        this.ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        this.ctx.fill();
    }
}

// Initialize particles
function initParticles() {
    const canvas = document.getElementById('sparkles');
    const ctx = canvas.getContext('2d');
    const mousePosition = { x: 0, y: 0 };
    let particles = [];

    // Set canvas size
    function setCanvasSize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    setCanvasSize();

    // Track mouse position
    window.addEventListener('mousemove', (e) => {
        mousePosition.x = e.clientX;
        mousePosition.y = e.clientY;
    });

    // Create particles
    const particleOptions = {
        minSize: 0.6,
        maxSize: 1.4,
        particleColor: '#FFFFFF',
        particleDensity: 100
    };

    for (let i = 0; i < particleOptions.particleDensity; i++) {
        particles.push(new Particle(canvas, ctx, mousePosition, particleOptions));
    }

    // Animation loop
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(particle => {
            particle.update();
            particle.draw();
        });
        requestAnimationFrame(animate);
    }

    // Handle resize
    window.addEventListener('resize', () => {
        setCanvasSize();
        particles = [];
        for (let i = 0; i < particleOptions.particleDensity; i++) {
            particles.push(new Particle(canvas, ctx, mousePosition, particleOptions));
        }
    });

    animate();
}

// Floating papers animation
function initFloatingPapers() {
    const container = document.getElementById('floating-papers');
    const paperCount = 6;

    for (let i = 0; i < paperCount; i++) {
        const paper = document.createElement('div');
        paper.className = 'paper';
        paper.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <path d="M14 2v6h6"/>
                <path d="M16 13H8"/>
                <path d="M16 17H8"/>
                <path d="M10 9H8"/>
            </svg>
        `;

        // Random initial position
        paper.style.left = `${Math.random() * 100}%`;
        paper.style.top = `${Math.random() * 100}%`;

        // Animation
        paper.style.animation = `float ${6 + Math.random() * 4}s ease-in-out infinite`;
        paper.style.animationDelay = `${Math.random() * 5}s`;

        container.appendChild(paper);
    }
}

// Robot animation
function initRobotAnimation() {
    const robot = document.querySelector('.bot-large');
    robot.style.animation = 'float 4s ease-in-out infinite';
}

// Initialize everything when the page loads
document.addEventListener('DOMContentLoaded', () => {
    initParticles();
    initFloatingPapers();
    initRobotAnimation();
});
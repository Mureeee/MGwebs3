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
        this.alpha = Math.random() * 0.5 + 0.5; // Transparencia variable
        this.originalSize = this.size;
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
            this.size = this.originalSize * 1.5; // Aumenta el tamaño cerca del mouse
        } else {
            this.size = this.originalSize; // Vuelve al tamaño original
        }
    }

    draw() {
        this.ctx.fillStyle = `rgba(255, 255, 255, ${this.alpha})`;
        this.ctx.beginPath();
        this.ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        this.ctx.fill();
    }
}

// Initialize particles
function initParticles() {
    const canvas = document.getElementById('sparkles');
    if (!canvas) return; // Evita errores si no existe el canvas
    
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

// Animación de carga de productos
function initProductAnimation() {
    const productos = document.querySelectorAll('.producto-card');
    
    productos.forEach((producto, index) => {
        producto.style.opacity = '0';
        producto.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            producto.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
            producto.style.opacity = '1';
            producto.style.transform = 'translateY(0)';
        }, index * 150); // Incrementado el delay entre productos
    });

    // Efecto hover mejorado en los productos
    productos.forEach(producto => {
        producto.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
            this.style.boxShadow = '0 20px 30px rgba(0, 0, 0, 0.3)';
        });
        
        producto.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.2)';
        });
    });
}

// Efecto en botones
function initButtonEffects() {
    const botones = document.querySelectorAll('.btn-comprar');
    
    botones.forEach(boton => {
        boton.addEventListener('mouseover', function() {
            this.style.transform = 'scale(1.05)';
            this.style.boxShadow = '0 5px 15px rgba(59, 130, 246, 0.4)';
        });
        
        boton.addEventListener('mouseout', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });

        // Efecto de click
        boton.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.95)';
        });

        boton.addEventListener('mouseup', function() {
            this.style.transform = 'scale(1.05)';
        });
    });
}

// Inicializar todo cuando la página carga
document.addEventListener('DOMContentLoaded', () => {
    initParticles();
    initProductAnimation();
    initButtonEffects();
    
    // Añadir efecto de desplazamiento suave
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});

// Optimización del rendimiento
let ticking = false;
window.addEventListener('scroll', () => {
    if (!ticking) {
        window.requestAnimationFrame(() => {
            // Aquí puedes añadir efectos de scroll si los necesitas
            ticking = false;
        });
        ticking = true;
    }
});
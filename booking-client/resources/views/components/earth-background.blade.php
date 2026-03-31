{{-- resources/views/components/earth-background.blade.php --}}
@push('styles')
    <style>
        #earth-container {
            position: relative;
            margin: 0 auto;
            width: 400px;  /* Увеличил размер */
            height: 400px; /* Увеличил размер */
            z-index: 100;
            pointer-events: auto;
            background: transparent; /* Прозрачный фон */
            opacity: 1;
            margin-bottom: 20px;
        }

        #earth-canvas {
            width: 100%;
            height: 100%;
            display: block;
            pointer-events: auto;
            cursor: grab;
            border-radius: 50%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        #earth-canvas:active {
            cursor: grabbing;
        }

        /* Контроллеры управления */
        .earth-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(10px);
            padding: 8px 12px;
            border-radius: 20px;
            display: flex;
            gap: 10px;
            font-family: system-ui, -apple-system, sans-serif;
            pointer-events: auto;
        }

        .earth-controls button {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 16px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .earth-controls button:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.05);
        }

        /* Анимация появления */
        @keyframes earthAppear {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        #earth-container {
            animation: earthAppear 0.8s ease-out;
        }

        /* Убираем свечение */
        #earth-container::before {
            display: none;
        }

        /* Адаптация для мобильных */
        @media (max-width: 768px) {
            #earth-container {
                width: 300px;
                height: 300px;
            }

            .earth-controls {
                display: none;
            }
        }
    </style>
@endpush

<div id="earth-container">
    <canvas id="earth-canvas"></canvas>
</div>

@push('scripts')
    <script type="importmap">
        {
            "imports": {
                "three": "https://unpkg.com/three@0.128.0/build/three.module.js",
                "three/addons/": "https://unpkg.com/three@0.128.0/examples/jsm/"
            }
        }
    </script>

    <script type="module">
        import * as THREE from 'three';
        import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

        // --- Инициализация ---
        const canvas = document.getElementById('earth-canvas');
        const container = document.getElementById('earth-container');

        const width = container.clientWidth;
        const height = container.clientHeight;

        const scene = new THREE.Scene();
        scene.background = null; // Полностью прозрачный фон

        const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
        camera.position.set(0, 0, 0);

        const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setClearColor(0x000000, 0); // Полностью прозрачный

        const controls = new OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;
        controls.autoRotate = false;
        controls.enableZoom = false;
        controls.enablePan = false;
        controls.zoomSpeed = 0.5;
        controls.rotateSpeed = 0.8;
        controls.maxDistance = 7;
        controls.minDistance = 3.5;
        controls.target.set(0, 0, 0);

        const textureLoader = new THREE.TextureLoader();
        const earthMap = textureLoader.load('https://threejs.org/examples/textures/planets/earth_atmos_2048.jpg');
        const earthSpecularMap = textureLoader.load('https://threejs.org/examples/textures/planets/earth_specular_2048.jpg');
        const earthNormalMap = textureLoader.load('https://threejs.org/examples/textures/planets/earth_normal_2048.jpg');
        const cloudMap = textureLoader.load('https://threejs.org/examples/textures/planets/earth_clouds_1024.png');

        // Земля
        const earthGeometry = new THREE.SphereGeometry(1.4, 128, 128); // Увеличил размер
        const earthMaterial = new THREE.MeshPhongMaterial({
            map: earthMap,
            specularMap: earthSpecularMap,
            normalMap: earthNormalMap,
            specular: new THREE.Color('grey'),
            shininess: 8,
            emissive: new THREE.Color(0x112233),
            emissiveIntensity: 0.05
        });
        const earth = new THREE.Mesh(earthGeometry, earthMaterial);
        scene.add(earth);

        // Облака
        const cloudGeometry = new THREE.SphereGeometry(1.42, 128, 128);
        const cloudMaterial = new THREE.MeshPhongMaterial({
            map: cloudMap,
            transparent: true,
            opacity: 0.15,
            blending: THREE.AdditiveBlending
        });
        const clouds = new THREE.Mesh(cloudGeometry, cloudMaterial);
        scene.add(clouds);

        // Убираем атмосферу (свечение)
        // Убираем звезды

        // Освещение
        const ambientLight = new THREE.AmbientLight(0x444444);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 1.3);
        directionalLight.position.set(5, 3, 5);
        scene.add(directionalLight);

        const fillLight = new THREE.PointLight(0x88aaff, 0.4);
        fillLight.position.set(2, 1, 3);
        scene.add(fillLight);

        const backLight = new THREE.PointLight(0xffaa88, 0.2);
        backLight.position.set(-2, -1, -3);
        scene.add(backLight);

        // Медленное вращение
        let isRotating = true;
        let earthRotationSpeed = 0.0012;
        let cloudRotationSpeed = 0.0013;

        const toggleBtn = document.getElementById('toggle-rotation');
        const resetBtn = document.getElementById('reset-view');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                isRotating = !isRotating;
                toggleBtn.textContent = isRotating ? '⏸️ Пауза' : '▶️ Вращать';
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                camera.position.set(0, 0, 5);
                controls.target.set(0, 0, 0);
                controls.update();
            });
        }

        let targetRotationX = 0;
        let targetRotationY = 0;
        let currentRotationX = 0;
        let currentRotationY = 0;

        container.addEventListener('mousemove', (event) => {
            if (!isRotating) {
                const rect = container.getBoundingClientRect();
                const x = (event.clientX - rect.left) / rect.width - 0.5;
                const y = (event.clientY - rect.top) / rect.height - 0.5;
                targetRotationY = x * 0.3;
                targetRotationX = y * 0.2;
            }
        });

        function animate() {
            requestAnimationFrame(animate);

            if (isRotating) {
                earth.rotation.y += earthRotationSpeed;
                clouds.rotation.y += cloudRotationSpeed;
            } else {
                currentRotationX += (targetRotationX - currentRotationX) * 0.05;
                currentRotationY += (targetRotationY - currentRotationY) * 0.05;
                earth.rotation.x = currentRotationX;
                earth.rotation.y = currentRotationY;
                clouds.rotation.x = currentRotationX;
                clouds.rotation.y = currentRotationY;
            }

            controls.update();
            renderer.render(scene, camera);
        }

        animate();

        const resizeObserver = new ResizeObserver(() => {
            const newWidth = container.clientWidth;
            const newHeight = container.clientHeight;
            camera.aspect = newWidth / newHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(newWidth, newHeight);
        });

        resizeObserver.observe(container);

        console.log('3D Earth - увеличенный размер 400px, без звезд и фона');
    </script>
@endpush

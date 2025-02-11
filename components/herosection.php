<section class="relative pt-0 pb-28 md:py-20 overflow-hidden" style="font-family:poppins">
  <!-- Animated background elements -->
  <div class="absolute inset-0 z-0">
    <div class="absolute w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -top-48 -left-48 animate-pulse"></div>
    <div class="absolute w-96 h-96 bg-purple-500/10 rounded-full blur-3xl -bottom-48 -right-48 animate-pulse"></div>
  </div>

  <div class="max-w-screen-xl mx-auto px-4 md:px-8 relative z-10">
    <div class="flex flex-col-reverse md:flex-row items-center gap-12">
      <!-- Text Content -->
      <div class="flex-1 space-y-6 transform transition-all duration-500 hover:scale-[1.01]">
      <h1 class="text-sm text-indigo-600 font-medium animate-fade-in-up">
  <span class="inline-block animate-bounce">🎉</span> Over 200 unforgettable events booked
</h1>

        <h2 class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent animate-slide-in-left">
  Discover, Book, and Enjoy Your Next Event Experience
</h2>

<p class="text-gray-600 text-lg animate-fade-in delay-100">
  Find the best events near you, book your tickets seamlessly, and get ready to enjoy unforgettable experiences.
</p>


        <!-- Buttons with hover effects -->
        <div class="flex flex-col sm:flex-row gap-4 animate-fade-in delay-200">
          <a href="#" class="relative inline-flex items-center justify-center px-6 py-3.5 text-white bg-indigo-600 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
            <span class="font-medium">Let's get started</span>
            <div class="ml-2 w-5 h-5 animate-bounce-right">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"></path>
              </svg>
            </div>
          </a>
          
         
        </div>
      </div>

      <!-- Image with floating animation -->
      <div class="relative w-full max-w-lg mx-auto">
  <!-- Image Container -->
  <div class="relative rounded-2xl overflow-hidden shadow-2xl hover:shadow-3xl transition-shadow duration-300">
    <img 
      id="slider-image"
      src="https://i.ibb.co/mVWMjdkS/img.jpg" 
      alt="Startup growth" 
      class="w-full h-full object-cover transition-opacity duration-1000 opacity-100"
      loading="lazy"
    />
    <div class="absolute inset-0 bg-indigo-600/10 mix-blend-multiply"></div>
  </div>
</div>
    </div>

   

  </div>

  <style>
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }
    @keyframes scroll-horizontal {
      0% { transform: translateX(0); }
      100% { transform: translateX(-100%); }
    }
    .animate-float {
      animation: float 6s ease-in-out infinite;
    }
    .animate-scroll-horizontal {
      animation: scroll-horizontal 30s linear infinite;
    }
    .animate-fade-in {
      opacity: 0;
      animation: fadeIn 0.6s ease-out forwards;
    }
    .animate-fade-in-up {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.6s ease-out forwards;
    }
    .animate-slide-in-left {
      opacity: 0;
      transform: translateX(-40px);
      animation: slideInLeft 0.6s ease-out forwards;
    }
    @keyframes fadeIn { to { opacity: 1; } }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    @keyframes slideInLeft { to { opacity: 1; transform: translateX(0); } }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
  </style>
</section>
<script>
  const images = [
    "https://i.ibb.co/mVWMjdkS/img.jpg", 
    "https://cdn.prod.website-files.com/655e0fa544c67c1ee5ce01c7/655e0fa544c67c1ee5ce0f7c_how-to-start-a-band-and-get-booked-header.jpeg",
    "https://img.freepik.com/premium-photo/live-music-concert-stage-background_800563-6860.jpg"
  ];

  let currentIndex = 0;
  const sliderImage = document.getElementById("slider-image");

  function changeImage() {
    currentIndex = (currentIndex + 1) % images.length;
    sliderImage.classList.add("opacity-0");

    setTimeout(() => {
      sliderImage.src = images[currentIndex];
      sliderImage.classList.remove("opacity-0");
    }, 500);
  }

  setInterval(changeImage, 3000);
</script>
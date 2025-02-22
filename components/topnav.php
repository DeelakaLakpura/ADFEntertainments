<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creative Animated Menu</title>
  <style>
   
    @keyframes slideIn {
      0% {
        transform: translateX(-100%);
      }
      100% {
        transform: translateX(0);
      }
    }

    @layer utilities {
      .menu-item {
        position: relative;
      }
      
      .menu-item:hover {
        @apply text-blue-400 transform scale-105 transition-all duration-300 ease-in-out;
      }
      
      .menu-item::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -5px;
        left: 0;
        background-color: #1e3a8a;
        transition: width 0.3s ease;
      }
      
      .menu-item:hover::after {
        width: 100%;
      }

      .hamburger-icon {
        transition: transform 0.3s ease;
      }

      .hamburger-icon.open {
        transform: rotate(90deg);
      }

      .mobile-menu {
        animation: slideIn 0.5s ease-out;
      }
    }
  </style>
</head>
<body class=" font-poppins">

  <!-- Navbar -->
  <nav class="bg-gray-800 shadow-lg">
    <div class="max-w-screen-xl mx-auto px-4 py-3">
      <div class="flex justify-between items-center">
        <!-- Logo or Title -->
        <a href="./index.php">
        <div class="flex items-center space-x-3">
      
  <img src="https://i.ibb.co/ynDRwHf2/logo.png" alt="Logo" class="w-10 h-10 rounded-full">
  <div class="text-white text-2xl font-bold">ADF Entertainments</div>
 
</div>
</a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-8">
          <a href="./" class="menu-item text-white flex items-center space-x-2">
            <i class="fas fa-home"></i>
            <span>Home</span>
          </a>
          <a href="./events.php" class="menu-item text-white flex items-center space-x-2">
          <i class="fas fa-calendar"></i>            <span>Events</span>
          </a>
          <a href="./event_managment.php" class="menu-item text-white flex items-center space-x-2">
            <i class="fas fa-briefcase"></i>
            <span>EM Companies</span>
          </a>
          <a href="./bands.php" class="menu-item text-white flex items-center space-x-2">
            <i class="fas fa-guitar"></i>
            <span>Bands</span>
          </a>
          <a href="./security_companies.php" class="menu-item text-white flex items-center space-x-2">
            <i class="fas fa-shield-alt"></i>
            <span>Security Companies</span>
          </a>
          <a href="./venues.php" class="menu-item text-white flex items-center space-x-2">
            <i class="fas fa-building"></i>
            <span>Venue Renters</span>
          </a>
          <a href="./contact.php" class="menu-item text-white flex items-center space-x-2">
            <i class="fas fa-envelope"></i>
            <span>Contact</span>
          </a>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center">
          <button id="mobile-menu-button" class="text-white">
            <i id="hamburger-icon" class="fas fa-bars hamburger-icon text-2xl"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-gray-800 py-3 mobile-menu">
      <div class="flex flex-col items-center space-y-4">
        <a href="#" class="menu-item text-white flex items-center space-x-2">
          <i class="fas fa-home"></i>
          <span>Home</span>
        </a>
        <a href="#about" class="menu-item text-white flex items-center space-x-2">
          <i class="fas fa-info-circle"></i>
          <span>About</span>
        </a>
        <a href="#contact" class="menu-item text-white flex items-center space-x-2">
          <i class="fas fa-envelope"></i>
          <span>Contact</span>
        </a>
        <a href="#event-management" class="menu-item text-white flex items-center space-x-2">
          <i class="fas fa-briefcase"></i>
          <span>Event Management Companies</span>
        </a>
        <a href="#bands" class="menu-item text-white flex items-center space-x-2">
          <i class="fas fa-guitar"></i>
          <span>Bands</span>
        </a>
        <a href="#security" class="menu-item text-white flex items-center space-x-2">
          <i class="fas fa-shield-alt"></i>
          <span>Security Companies</span>
        </a>
        <a href="#venues" class="menu-item text-white flex items-center space-x-2">
          <i class="fas fa-building"></i>
          <span>Venue Renters</span>
        </a>
      </div>
    </div>
  </nav>

  <!-- JavaScript for Mobile Menu Toggle and Hamburger Animation -->
  <script>
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    
    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
      hamburgerIcon.classList.toggle('open');
    });
  </script>

</body>
</html>

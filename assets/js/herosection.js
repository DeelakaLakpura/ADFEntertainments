const images = [
    "https://i.ibb.co/mVWMjdkS/img.jpg", 
    "https://i.ibb.co/album2.jpg",
    "https://i.ibb.co/album3.jpg"
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
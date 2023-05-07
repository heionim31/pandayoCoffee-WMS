<!-- Add a fixed position div to cover the whole screen -->
<div id="loading-overlay"></div>

<!-- Add the loading animation -->
<div id="loading-animation"></div>

<style>
  /* Style the loading overlay */
  #loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    transition: opacity 0.5s ease-in-out;
  }

  /* Style the loading animation */
  #loading-animation {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10000;
  }
</style>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.4/lottie.min.js"></script>
<script>
  // Load the Lottie animation as usual
  var animation = lottie.loadAnimation({
    container: document.getElementById('loading-animation'),
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: 'https://assets2.lottiefiles.com/private_files/lf30_spMO1c.json'
  });

  // Add an event listener to detect when the page has finished loading
  window.addEventListener('load', function() {
    // Get a reference to the loading overlay and remove it from the DOM
    var loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.parentNode.removeChild(loadingOverlay);
    
    // Destroy the animation and hide the loading animation element after a short delay
    setTimeout(function() {
      animation.destroy();
      document.getElementById('loading-animation').style.display = 'none';
    });
  });

  // Add an event listener to the window object for the "beforeunload" event
  window.addEventListener('beforeunload', function() {
    // Stop the animation
    animation.stop();
  });
</script>

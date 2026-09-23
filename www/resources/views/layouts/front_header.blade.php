<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top p-lg-0" data-aos="fade-down" data-aos-duration="600">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ $logo }}" alt="ProFinder Logo" class="brand-icon">

        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ Request::is('/')? 'active':''}}" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('about')? 'active':''}}" href="/about">About Us</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('subscription')? 'active':''}}" href="/subscription">Subscription</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('services')? 'active':''}}" href="/services">Service</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('blogs')? 'active':''}}" href="/blogs">Blogs</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('faqs')? 'active':''}}" href="/faqs">FAQ</a></li>
                <li class="nav-item"><a class="nav-link text-blue {{ Request::is('service-professional')? 'active':''}}" href="/service-professional">List as a partner</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('contact-us')? 'active':''}}" href="/contact-us">Contact Us</a></li>
            </ul>
            <!-- <div class="d-flex gap-2">
                <a href="/login" class="btn btn-outline-dark btn-login">Login</a>
                <a href="/register" class="btn btn-orange">Sign up</a>
            </div> -->
        </div>
    </div>
</nav>
<script>
document.addEventListener("DOMContentLoaded", function() {
  var header = document.querySelector(".navbar, header");
  if(header) {
    var height = header.offsetHeight;
    document.body.style.paddingTop = height + "px";
  }
});
</script>

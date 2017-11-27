<template>
  <header class="header">
    <!-- Main Navbar-->
    <nav class="navbar navbar-toggleable-md">
      <div class="search-area">
        <div class="search-area-inner d-flex align-items-center justify-content-center">
          <div class="close-btn"><i class="icon-close"></i></div>
          <div class="row">
            <div class="col-md-8 push-md-2">
              <form action="#">
                <div class="form-group">
                  <input type="search" name="search" id="search" placeholder="Çfare po kerkon?">
                  <button type="submit" class="submit"><i class="icon-search-1"></i></button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <!-- Navbar Brand -->
        <div class="navbar-header d-flex align-items-center justify-content-between">
          <!-- Navbar Brand --><a href="#" class="navbar-brand">Te Rejat</a>
          <!-- Toggle Button-->
          <button type="button" data-toggle="collapse" data-target="#navbarcollapse" aria-controls="navbarcollapse"
                  aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler">
            <span></span><span></span><span></span>
          </button>
        </div>
        <!-- Navbar Menu -->
        <div id="navbarcollapse" class="collapse navbar-collapse">
          <ul class="navbar-nav ml-auto">
            <!-- Search-->
            <li class="nav-item"><a href="#" class="nav-link">Kryefaqe</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Blog</a></li>
            <li class="nav-item hidden-md-down"><a class="nav-link"> | </a></li>
          </ul>
          <div class="navbar-text"><a href="#" class="search-btn"><i class="icon-search-1"></i></a></div>
        </div>
      </div>
    </nav>
  </header>

</template>

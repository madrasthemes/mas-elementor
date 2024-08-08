// Function to show the tab on hover
document.querySelectorAll('.mas-tab-hover .mas-elementor-nav-tab-li.nav-item').forEach(function(tab) {
    tab.addEventListener('mouseenter', function() {
        var link = this.querySelector('.mas-nav-link');
        var target = link.getAttribute('data-bs-target');
        var tabContent = document.querySelector(target);
  
        // Hide all other tabs
        document.querySelectorAll('.tab-pane').forEach(function(pane) {
            pane.classList.remove('active', 'show');
        });
  
        // Show the current tab
        tabContent.classList.add('active', 'show');
  
        // Deactivate other nav links
        document.querySelectorAll('.mas-nav-link').forEach(function(navLink) {
            navLink.classList.remove('active');
        });
  
        // Activate the hovered nav link
        link.classList.add('active');
    });
  
    // Function to hide the tab when mouse leaves the tab area
    tab.addEventListener('mouseleave', function() {
        var link = this.querySelector('.mas-nav-link');
        var target = link.getAttribute('data-bs-target');
        var tabContent = document.querySelector(target);
  
        // Hide the current tab
        tabContent.classList.remove('active', 'show');
  
        // Deactivate the nav link
        link.classList.remove('active');
    });
  });
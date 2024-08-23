const tabHoverContainer = document.querySelector('.mas-tab-hover');
const tabContentContainer = document.querySelector('.tab-content');

// Add event listener for hovering over tabs
document.querySelectorAll('.mas-tab-hover .mas-elementor-nav-tab-li').forEach(tab => {
  tab.addEventListener('mouseenter', function () {
    const target = this.querySelector('.mas-nav-link').getAttribute('data-bs-target');

    // Deactivate all tabs and hide all tab panes
    document.querySelectorAll('.mas-tab-hover .mas-nav-link').forEach(link => {
      link.classList.remove('active');
      document.querySelector(link.getAttribute('data-bs-target')).classList.remove('active', 'show');
    });

    // Activate the hovered tab and show its content
    this.querySelector('.mas-nav-link').classList.add('active');
    document.querySelector(target).classList.add('active', 'show');
  });
});

// Event listener to hide content when cursor leaves both containers
let timeoutId;

function hideTabsContent() {
  timeoutId = setTimeout(() => {
    document.querySelectorAll('.mas-tab-hover .mas-nav-link').forEach(link => {
      link.classList.remove('active');
      document.querySelector(link.getAttribute('data-bs-target')).classList.remove('active', 'show');
    });
  }, 100); // Adjust delay as needed to prevent flickering
}

function clearHideTimeout() {
  if (timeoutId) {
    clearTimeout(timeoutId);
  }
}

// Apply the hide and clear functions on mouseleave and mouseenter respectively
if ( tabHoverContainer ) {
  tabHoverContainer.addEventListener('mouseleave', hideTabsContent);
  tabHoverContainer.addEventListener('mouseenter', clearHideTimeout);
}
if ( tabContentContainer ) { 
  tabContentContainer.addEventListener('mouseleave', hideTabsContent);
  tabContentContainer.addEventListener('mouseenter', clearHideTimeout);
}

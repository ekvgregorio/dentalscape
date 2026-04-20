    const features = [
      { name: "Book Appointments", url: "/dentalscape/booking/", keywords: ["book", "appointment", "schedule", "reserve", "arranged"] },
      { name: "Reschedule Appointments", url: "/dentalscape/reschedule/", keywords: ["reschedule", "records", "appointment", "schedule"] },
      { name: "Cancel Appointments", url: "/dentalscape/reschedule/", keywords: ["cancel", "appointment", "manage", "delete"] },
      { name: "Pending Appointments", url: "/dentalscape/pending/", keywords: ["pending", "appointment", "schedule", "waiting"] },
      { name: "User Profile", url: "/dentalscape/profile/", keywords: ["user", "information", "profile", "verification"] },
      { name: "Health Records", url: "/dentalscape/health-records/", keywords: ["health", "manage", "records", "medical", "information"] },
    ];

    function createSuggestionsDropdown() {
      const searchInput = document.getElementById('navbar-search-input');
      const inputParent = searchInput.parentElement;
      
      const dropdown = document.createElement('div');
      dropdown.id = 'search-suggestions';
      dropdown.className = 'search-suggestions-dropdown';
      dropdown.style.display = 'none';
      dropdown.style.position = 'absolute';
      dropdown.style.top = '100%';
      dropdown.style.left = '0';
      dropdown.style.right = '0';
      dropdown.style.zIndex = '1000';
      dropdown.style.backgroundColor = '#fff';
      dropdown.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
      dropdown.style.borderRadius = '0 0 4px 4px';
      dropdown.style.maxHeight = '300px';
      dropdown.style.overflowY = 'auto';
      
      inputParent.style.position = 'relative';
      inputParent.appendChild(dropdown);
      
      return dropdown;
    }

    function initSearch() {
      const searchInput = document.getElementById('navbar-search-input');
      const dropdown = createSuggestionsDropdown();
      
      searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        if (query.length < 1) {
          dropdown.style.display = 'none';
          return;
        }
        
        const filteredFeatures = features.filter(feature => {
          if (feature.name.toLowerCase().startsWith(query)) return true;
          
          return feature.keywords.some(keyword => keyword.toLowerCase().startsWith(query));
        });

        if (filteredFeatures.length > 0) {
          dropdown.innerHTML = '';
          filteredFeatures.forEach(feature => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.textContent = feature.name;
            item.style.padding = '10px 15px';
            item.style.cursor = 'pointer';
            item.style.borderBottom = '1px solid #eee';
            
            const matchText = feature.name;
            const matchIndex = matchText.toLowerCase().indexOf(query);
            if (matchIndex >= 0) {
              item.innerHTML = matchText.substring(0, matchIndex) + 
                              '<strong>' + matchText.substring(matchIndex, matchIndex + query.length) + '</strong>' + 
                              matchText.substring(matchIndex + query.length);
            }
            
            item.addEventListener('mouseover', function() {
              this.style.backgroundColor = '#f5f5f5';
            });
            
            item.addEventListener('mouseout', function() {
              this.style.backgroundColor = 'transparent';
            });
            
            item.addEventListener('click', function() {
              window.location.href = feature.url;
            });
            
            dropdown.appendChild(item);
          });
          
          dropdown.style.display = 'block';
        } else {
          dropdown.style.display = 'none';
        }
      });
      
      document.addEventListener('click', function(e) {
        if (e.target !== searchInput && !dropdown.contains(e.target)) {
          dropdown.style.display = 'none';
        }
      });
      
      searchInput.addEventListener('keydown', function(e) {
        if (dropdown.style.display === 'none') return;
        
        const items = dropdown.querySelectorAll('.suggestion-item');
        const activeItem = dropdown.querySelector('.suggestion-item.active');
        let activeIndex = -1;
        
        if (activeItem) {
          for (let i = 0; i < items.length; i++) {
            if (items[i] === activeItem) {
              activeIndex = i;
              break;
            }
          }
        }
        
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          if (activeItem) activeItem.classList.remove('active');
          
          activeIndex = (activeIndex + 1) % items.length;
          items[activeIndex].classList.add('active');
          items[activeIndex].style.backgroundColor = '#e9ecef';
        }
        else if (e.key === 'ArrowUp') {
          e.preventDefault();
          if (activeItem) activeItem.classList.remove('active');
          
          activeIndex = (activeIndex - 1 + items.length) % items.length;
          items[activeIndex].classList.add('active');
          items[activeIndex].style.backgroundColor = '#e9ecef';
        }
        else if (e.key === 'Enter') {
          e.preventDefault();
          if (activeItem) {
            window.location.href = features[activeIndex].url;
          }
        }
      });
    }

    document.addEventListener('DOMContentLoaded', initSearch);
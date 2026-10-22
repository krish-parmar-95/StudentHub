/* Practical 6: Fetch API, Search, Filter, Sort, Pagination */

// State variables
var currentTab = 'events';
var eventsData = [];
var studentsData = [];
var faqsData = [];
var currentPage = 1;
var perPage = 5;

// Switch between tabs
function switchTab(tab) {
  currentTab = tab;
  currentPage = 1;
  // update active tab button styling
  document.querySelectorAll('.tab-btn').forEach(function(btn) { btn.classList.remove('active-tab'); });
  document.getElementById('tab-' + tab).classList.add('active-tab');
  // update filter and sort options based on tab
  updateControls();
  // load data if not loaded, then apply filters
  loadData(tab);
}

// Update filter/sort dropdowns based on current tab
function updateControls() {
  var filterSelect = document.getElementById('filter-select');
  var sortSelect = document.getElementById('sort-select');
  var searchInput = document.getElementById('search-input');
  searchInput.value = '';
  
  // Clear and rebuild filter options
  filterSelect.innerHTML = '<option value="">All</option>';
  sortSelect.innerHTML = '<option value="">Default</option>';
  
  if (currentTab === 'events') {
    ['Academic', 'Cultural', 'Sports', 'Workshop', 'Technical'].forEach(function(t) {
      filterSelect.innerHTML += '<option value="' + t + '">' + t + '</option>';
    });
    sortSelect.innerHTML += '<option value="title-asc">Title A-Z</option>';
    sortSelect.innerHTML += '<option value="title-desc">Title Z-A</option>';
    sortSelect.innerHTML += '<option value="date-asc">Date (Earliest)</option>';
    sortSelect.innerHTML += '<option value="date-desc">Date (Latest)</option>';
  } else if (currentTab === 'students') {
    ['Computer Science', 'Information Technology', 'Electronics', 'Mechanical', 'Civil'].forEach(function(d) {
      filterSelect.innerHTML += '<option value="' + d + '">' + d + '</option>';
    });
    sortSelect.innerHTML += '<option value="name-asc">Name A-Z</option>';
    sortSelect.innerHTML += '<option value="name-desc">Name Z-A</option>';
    sortSelect.innerHTML += '<option value="cgpa-asc">CGPA (Low to High)</option>';
    sortSelect.innerHTML += '<option value="cgpa-desc">CGPA (High to Low)</option>';
  } else if (currentTab === 'faqs') {
    ['Academic', 'Technical', 'Admission', 'General', 'Examination'].forEach(function(c) {
      filterSelect.innerHTML += '<option value="' + c + '">' + c + '</option>';
    });
    sortSelect.innerHTML += '<option value="question-asc">Question A-Z</option>';
    sortSelect.innerHTML += '<option value="question-desc">Question Z-A</option>';
  }
}

// Fetch data using Fetch API
function loadData(tab) {
  var display = document.getElementById('data-display');
  var dataArray;
  
  if (tab === 'events' && eventsData.length > 0) { applyFilters(); return; }
  if (tab === 'students' && studentsData.length > 0) { applyFilters(); return; }
  if (tab === 'faqs' && faqsData.length > 0) { applyFilters(); return; }
  
  // Show loading
  display.innerHTML = '<p class="loading-text">Loading data...</p>';
  
  var url = '../data/' + tab + '.json';
  
  fetch(url)
    .then(function(response) {
      if (!response.ok) { throw new Error('Failed to load'); }
      return response.json();
    })
    .then(function(data) {
      if (tab === 'events') eventsData = data;
      else if (tab === 'students') studentsData = data;
      else if (tab === 'faqs') faqsData = data;
      applyFilters();
    })
    .catch(function(error) {
      display.innerHTML = '<p class="error-text">Failed to load data. Please try again. (' + error.message + ')</p>';
      document.getElementById('pagination').innerHTML = '';
    });
}

// Apply search, filter, sort and then paginate
function applyFilters() {
  var data = [];
  if (currentTab === 'events') data = eventsData.slice();
  else if (currentTab === 'students') data = studentsData.slice();
  else if (currentTab === 'faqs') data = faqsData.slice();
  
  var searchVal = document.getElementById('search-input').value.toLowerCase();
  var filterVal = document.getElementById('filter-select').value;
  var sortVal = document.getElementById('sort-select').value;
  
  // SEARCH
  if (searchVal) {
    data = data.filter(function(item) {
      if (currentTab === 'events') return item.title.toLowerCase().includes(searchVal) || item.description.toLowerCase().includes(searchVal);
      if (currentTab === 'students') return item.name.toLowerCase().includes(searchVal) || item.email.toLowerCase().includes(searchVal);
      if (currentTab === 'faqs') return item.question.toLowerCase().includes(searchVal) || item.answer.toLowerCase().includes(searchVal);
      return true;
    });
  }
  
  // FILTER
  if (filterVal) {
    data = data.filter(function(item) {
      if (currentTab === 'events') return item.type === filterVal;
      if (currentTab === 'students') return item.department === filterVal;
      if (currentTab === 'faqs') return item.category === filterVal;
      return true;
    });
  }
  
  // SORT
  if (sortVal) {
    var parts = sortVal.split('-');
    var field = parts[0];
    var order = parts[1];
    data.sort(function(a, b) {
      var valA = a[field];
      var valB = b[field];
      if (typeof valA === 'string') { valA = valA.toLowerCase(); valB = valB.toLowerCase(); }
      if (valA < valB) return order === 'asc' ? -1 : 1;
      if (valA > valB) return order === 'asc' ? 1 : -1;
      return 0;
    });
  }
  
  // PAGINATION
  var totalItems = data.length;
  var totalPages = Math.ceil(totalItems / perPage);
  if (currentPage > totalPages) currentPage = 1;
  var start = (currentPage - 1) * perPage;
  var end = start + perPage;
  var pageData = data.slice(start, end);
  
  // RENDER
  if (currentTab === 'events') renderEvents(pageData, totalItems);
  else if (currentTab === 'students') renderStudents(pageData, totalItems);
  else if (currentTab === 'faqs') renderFAQs(pageData, totalItems);
  
  renderPagination(totalItems, totalPages);
}

// Render events as table
function renderEvents(data, total) {
  var display = document.getElementById('data-display');
  if (data.length === 0) { display.innerHTML = '<p>No events found.</p>'; return; }
  var html = '<p>Showing ' + data.length + ' of ' + total + ' events</p>';
  html += '<table border="1" cellpadding="8" cellspacing="0"><thead><tr>';
  html += '<th>Title</th><th>Type</th><th>Date</th><th>Venue</th><th>Time</th>';
  html += '</tr></thead><tbody>';
  data.forEach(function(e) {
    html += '<tr>';
    html += '<td><strong>' + e.title + '</strong><br><small>' + e.description + '</small></td>';
    html += '<td>' + e.type + '</td>';
    html += '<td>' + e.date + '</td>';
    html += '<td>' + e.venue + '</td>';
    html += '<td>' + e.time + '</td>';
    html += '</tr>';
  });
  html += '</tbody></table>';
  display.innerHTML = html;
}

// Render students as table
function renderStudents(data, total) {
  var display = document.getElementById('data-display');
  if (data.length === 0) { display.innerHTML = '<p>No students found.</p>'; return; }
  var html = '<p>Showing ' + data.length + ' of ' + total + ' students</p>';
  html += '<table border="1" cellpadding="8" cellspacing="0"><thead><tr>';
  html += '<th>Name</th><th>Email</th><th>Department</th><th>Sem</th><th>CGPA</th><th>City</th>';
  html += '</tr></thead><tbody>';
  data.forEach(function(s) {
    html += '<tr>';
    html += '<td>' + s.name + '</td>';
    html += '<td>' + s.email + '</td>';
    html += '<td>' + s.department + '</td>';
    html += '<td>' + s.semester + '</td>';
    html += '<td>' + s.cgpa + '</td>';
    html += '<td>' + s.city + '</td>';
    html += '</tr>';
  });
  html += '</tbody></table>';
  display.innerHTML = html;
}

// Render FAQs as details/summary
function renderFAQs(data, total) {
  var display = document.getElementById('data-display');
  if (data.length === 0) { display.innerHTML = '<p>No FAQs found.</p>'; return; }
  var html = '<p>Showing ' + data.length + ' of ' + total + ' FAQs</p>';
  data.forEach(function(f) {
    html += '<details style="margin-bottom:10px; border:1px solid #ddd; padding:10px;">';
    html += '<summary style="cursor:pointer; font-weight:bold; color:#4a90d9;">' + f.question + ' <small>(' + f.category + ')</small></summary>';
    html += '<p style="margin-top:8px; color:#555;">' + f.answer + '</p>';
    html += '</details>';
  });
  display.innerHTML = html;
}

// Render pagination buttons
function renderPagination(totalItems, totalPages) {
  var pag = document.getElementById('pagination');
  if (totalPages <= 1) { pag.innerHTML = ''; return; }
  
  var html = '';
  html += '<button onclick="goToPage(' + (currentPage - 1) + ')"' + (currentPage === 1 ? ' disabled' : '') + '>Previous</button>';
  html += '<span class="page-info">Page ' + currentPage + ' of ' + totalPages + '</span>';
  html += '<button onclick="goToPage(' + (currentPage + 1) + ')"' + (currentPage === totalPages ? ' disabled' : '') + '>Next</button>';
  pag.innerHTML = html;
}

// Go to specific page
function goToPage(page) {
  currentPage = page;
  applyFilters();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  updateControls();
  loadData('events');
});

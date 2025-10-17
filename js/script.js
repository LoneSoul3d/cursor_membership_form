document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    
    // Load countries and occupations on page load
    loadCountries();
    loadOccupations();
    
    // Event listeners for location dropdowns
    document.getElementById('country').addEventListener('change', function() {
        const countryId = this.value;
        if (countryId) {
            loadProvinces(countryId);
        } else {
            resetProvinces();
            resetCities();
            resetTowns();
        }
    });
    
    document.getElementById('province').addEventListener('change', function() {
        const provinceId = this.value;
        if (provinceId) {
            loadCities(provinceId);
        } else {
            resetCities();
            resetTowns();
        }
    });
    
    document.getElementById('city').addEventListener('change', function() {
        const cityId = this.value;
        if (cityId) {
            loadTowns(cityId);
        } else {
            resetTowns();
        }
    });
    
    // CNIC and Mobile validation on blur
    document.getElementById('cnic').addEventListener('blur', validateCNIC);
    document.getElementById('mobile').addEventListener('blur', validateMobile);
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        clearErrors();
        
        // Validate required fields
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                showError(field, 'This field is required');
                isValid = false;
            }
        });
        
        // Validate CNIC
        if (!validateCNIC()) {
            isValid = false;
        }
        
        // Validate Mobile
        if (!validateMobile()) {
            isValid = false;
        }
        
        // Validate email
        const emailInput = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailInput.value && !emailRegex.test(emailInput.value)) {
            showError(emailInput, 'Please enter a valid email address');
            isValid = false;
        }
        
        // Validate password length
        const passwordInput = document.getElementById('password');
        if (passwordInput.value && passwordInput.value.length < 6) {
            showError(passwordInput, 'Password must be at least 6 characters long');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Location loading functions
    function loadCountries() {
        showLoading('country', 'Loading countries...');
        
        fetch('ajax/get_locations.php?type=countries')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Check if response has error
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const countrySelect = document.getElementById('country');
                countrySelect.innerHTML = '<option value="">Select Country</option>';
                
                data.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.id;
                    option.textContent = country.name;
                    if (country.name === 'Pakistan') {
                        option.selected = true;
                        loadProvinces(country.id);
                    }
                    countrySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading countries:', error);
                showErrorInSelect('country', `Failed to load countries: ${error.message}`);
            });
    }
    
    function loadProvinces(countryId) {
        showLoading('province', 'Loading provinces...');
        
        fetch(`ajax/get_locations.php?type=provinces&country_id=${countryId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const provinceSelect = document.getElementById('province');
                provinceSelect.innerHTML = '<option value="">Select Province</option>';
                
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.id;
                    option.textContent = province.name;
                    if (province.name === 'Sindh') {
                        option.selected = true;
                        loadCities(province.id);
                    }
                    provinceSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading provinces:', error);
                showErrorInSelect('province', `Failed to load provinces: ${error.message}`);
            });
    }
    
    function loadCities(provinceId) {
        showLoading('city', 'Loading cities...');
        
        fetch(`ajax/get_locations.php?type=cities&province_id=${provinceId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const citySelect = document.getElementById('city');
                citySelect.innerHTML = '<option value="">Select City</option>';
                
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    if (city.name === 'Karachi') {
                        option.selected = true;
                        loadTowns(city.id);
                    }
                    citySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading cities:', error);
                showErrorInSelect('city', `Failed to load cities: ${error.message}`);
            });
    }
    
    function loadTowns(cityId) {
        showLoading('town', 'Loading towns...');
        
        fetch(`ajax/get_locations.php?type=towns&city_id=${cityId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const townSelect = document.getElementById('town');
                townSelect.innerHTML = '<option value="">Select Town</option>';
                
                data.forEach(town => {
                    const option = document.createElement('option');
                    option.value = town.id;
                    option.textContent = town.name;
                    townSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading towns:', error);
                showErrorInSelect('town', `Failed to load towns: ${error.message}`);
            });
    }
    
    function loadOccupations() {
        showLoading('occupation', 'Loading occupations...');
        
        fetch('ajax/get_locations.php?type=occupations')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const occupationSelect = document.getElementById('occupation');
                occupationSelect.innerHTML = '<option value="">Select Occupation</option>';
                
                data.forEach(occupation => {
                    const option = document.createElement('option');
                    option.value = occupation.name;
                    option.textContent = occupation.name;
                    occupationSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading occupations:', error);
                showErrorInSelect('occupation', `Failed to load occupations: ${error.message}`);
            });
    }
    
    function showLoading(selectId, message) {
        const select = document.getElementById(selectId);
        if (select) {
            select.innerHTML = `<option value="">${message}</option>`;
        }
    }
    
    function showErrorInSelect(selectId, message) {
        const select = document.getElementById(selectId);
        if (select) {
            select.innerHTML = `<option value="">${message}</option>`;
        }
    }
    
    function resetProvinces() {
        document.getElementById('province').innerHTML = '<option value="">Select Province</option>';
        resetCities();
    }
    
    function resetCities() {
        document.getElementById('city').innerHTML = '<option value="">Select City</option>';
        resetTowns();
    }
    
    function resetTowns() {
        document.getElementById('town').innerHTML = '<option value="">Select Town</option>';
    }
    
    // Validation functions
    function validateCNIC() {
        const cnicInput = document.getElementById('cnic');
        const cnicValue = cnicInput.value.replace(/\D/g, '');
        const cnicRegex = /^\d{13}$/;
        
        if (!cnicRegex.test(cnicValue)) {
            showError(cnicInput, 'CNIC must be 13 digits');
            return false;
        }
        
        // Check if CNIC already exists
        return checkExisting('cnic', cnicValue, cnicInput, 'CNIC already registered');
    }
    
    function validateMobile() {
        const mobileInput = document.getElementById('mobile');
        const mobileValue = mobileInput.value.replace(/\D/g, '');
        const mobileRegex = /^03\d{9}$/;
        
        if (!mobileRegex.test(mobileValue)) {
            showError(mobileInput, 'Mobile must be 11 digits starting with 03');
            return false;
        }
        
        // Check if mobile already exists
        return checkExisting('mobile', mobileValue, mobileInput, 'Mobile number already registered');
    }
    
    function checkExisting(field, value, inputElement, errorMessage) {
        return fetch('ajax/get_locations.php?type=check_existing', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `field=${field}&value=${value}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                showError(inputElement, errorMessage);
                return false;
            }
            return true;
        })
        .catch(error => {
            console.error('Error checking existing:', error);
            return true;
        });
    }
    
    function showError(field, message) {
        field.style.borderColor = '#e74c3c';
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.fontSize = '14px';
        errorDiv.style.marginTop = '5px';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    function clearErrors() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(error => error.remove());
        
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.style.borderColor = '#ddd';
        });
    }
    
    // CNIC formatting
    const cnicInput = document.getElementById('cnic');
    cnicInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 13) value = value.substring(0, 13);
        
        if (value.length > 5) {
            value = value.substring(0, 5) + '-' + value.substring(5);
        }
        if (value.length > 13) {
            value = value.substring(0, 13) + '-' + value.substring(13);
        }
        e.target.value = value;
    });
    
    // Mobile number formatting
    const mobileInput = document.getElementById('mobile');
    mobileInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.substring(0, 11);
        e.target.value = value;
    });
});
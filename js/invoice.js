document.addEventListener('DOMContentLoaded', function() {
    // Get the form elements
    const landRegistrySelect = document.getElementById('landRegistryNo');
    const tehsilSelect = document.getElementById('tehsil');
    const villageSelect = document.getElementById('village');
    const plotNumberInput = document.getElementById('plotNumber');
    const invoiceDetails = document.getElementById('invoiceDetails');

    // Populate Land Registry Numbers (101-120)
    populateLandRegistryNumbers();

    // Event listeners for dropdowns
    landRegistrySelect.addEventListener('change', updateTehsilOptions);
    tehsilSelect.addEventListener('change', updateVillageOptions);
    villageSelect.addEventListener('change', enablePlotNumber);

    function populateLandRegistryNumbers() {
        landRegistrySelect.innerHTML = '<option value="">Select Land Registry No.</option>';
        for (let i = 101; i <= 120; i++) {
            const option = document.createElement('option');
            option.value = i.toString();
            option.textContent = i.toString();
            landRegistrySelect.appendChild(option);
        }
    }

    async function loadSprinklerData() {
        try {
            const response = await fetch('handlers/get_sprinkler_data.php');
            const data = await response.json();
            
            if (data.success) {
                window.sprinklerData = data;
                console.log('Loaded sprinkler data:', data);
            } else {
                console.error('Failed to load sprinkler data:', data.message);
            }
        } catch (error) {
            console.error('Error loading sprinkler data:', error);
        }
    }

    // Load the sprinkler data for Tehsil and Village options
    loadSprinklerData();

    function updateTehsilOptions() {
        const selectedRegNo = landRegistrySelect.value;
        tehsilSelect.innerHTML = '<option value="">Select Tehsil</option>';
        villageSelect.innerHTML = '<option value="">Select Village</option>';
        plotNumberInput.value = '';
        plotNumberInput.disabled = true;
        
        if (selectedRegNo && window.sprinklerData && window.sprinklerData.tehsilMapping[selectedRegNo]) {
            const tehsils = window.sprinklerData.tehsilMapping[selectedRegNo];
            tehsils.forEach(tehsil => {
                const option = document.createElement('option');
                option.value = tehsil;
                option.textContent = tehsil;
                tehsilSelect.appendChild(option);
            });
        }
    }

    function updateVillageOptions() {
        const selectedRegNo = landRegistrySelect.value;
        const selectedTehsil = tehsilSelect.value;
        villageSelect.innerHTML = '<option value="">Select Village</option>';
        plotNumberInput.value = '';
        plotNumberInput.disabled = true;
        
        if (selectedRegNo && selectedTehsil && window.sprinklerData) {
            const key = selectedRegNo + '_' + selectedTehsil;
            const villages = window.sprinklerData.villageMapping[key] || [];
            villages.forEach(village => {
                const option = document.createElement('option');
                option.value = village;
                option.textContent = village;
                villageSelect.appendChild(option);
            });
        }
    }

    function enablePlotNumber() {
        const selectedRegNo = landRegistrySelect.value;
        const selectedTehsil = tehsilSelect.value;
        const selectedVillage = villageSelect.value;
        
        if (selectedVillage) {
            const key = selectedRegNo + '_' + selectedTehsil + '_' + selectedVillage;
            const plotData = window.sprinklerData.plotMapping[key];
            if (plotData) {
                plotNumberInput.value = plotData.plotNumbers;
                plotNumberInput.readOnly = true;
                plotNumberInput.disabled = false;
            }
        } else {
            plotNumberInput.value = '';
            plotNumberInput.readOnly = false;
            plotNumberInput.disabled = true;
        }
    }

    window.calculateCost = function() {
        const selectedRegNo = landRegistrySelect.value;
        const selectedTehsil = tehsilSelect.value;
        const selectedVillage = villageSelect.value;
        
        if (!selectedRegNo || !selectedTehsil || !selectedVillage) {
            alert('Please select all required fields');
            return;
        }

        const key = selectedRegNo + '_' + selectedTehsil + '_' + selectedVillage;
        const plotData = window.sprinklerData.plotMapping[key];

        if (plotData) {
            // Update the invoice details
            document.getElementById('area').textContent = plotData.area;
            document.getElementById('dimensions').textContent = plotData.dimensions;
            document.getElementById('sprinklers').textContent = plotData.sprinklers;
            document.getElementById('sensors').textContent = plotData.sensors;
            document.getElementById('totalCost').textContent = plotData.totalCost;
            
            // Show the invoice details
            invoiceDetails.style.display = 'block';
        } else {
            alert('No data found for the selected combination');
        }
    }
}); 
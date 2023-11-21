<style>
#searchInput {
    width: 95%;
    border: 1px solid #03322cb0;
    padding: 7px 26px;
    height: 40px;
    border-radius: 20px;
}

#results {
    margin-top: 10px;
    display: none;
    position: absolute;
    width: 92%;
    z-index: 1;
    background: #f8f8f8;
    border-radius: 10px;
}

.result-item {
    cursor: pointer;
    padding: 8px;
    border: 1px solid oklch(0.81 0.05 1.4 / 0.36)
}

.result-item:hover {
    background-color: #f0f0f0;
}

.selected {
    background-color: #007bff;
    color: #fff;
}
</style>


<input type="text" id="searchInput" placeholder="Enter your search term">
<div id="results"></div>

<script>
const searchInput = document.getElementById('searchInput');
const resultsContainer = document.getElementById('results');
let currentSelection = -1;

searchInput.addEventListener('input', function() {
    simulateServerRequest(searchInput.value, function(results) {
        displayResults(results);
    });
});

searchInput.addEventListener('keydown', function(event) {
    if (event.key === 'ArrowUp' && currentSelection > 0) {
        currentSelection--;
        updateSelection();
    } else if (event.key === 'ArrowDown' && currentSelection < resultsContainer.children.length - 1) {
        currentSelection++;
        updateSelection();
    } else if (event.key === 'Enter') {
        // Handle Enter key press
        event.preventDefault(); // Prevent submitting a form, if any
        if (currentSelection !== -1) {
            chooseOption(resultsContainer.children[currentSelection]);
        }
    }
});

async function simulateServerRequest(query, callback) {
    try {
        await fetch('<?php echo base_url()?>/OrderController/searchcustomer?key=' + query)
            .then(response => response.json())
            .then(data => {
                callback(data);
            })
            .catch(error => {
                console.log(error);
            });

    } catch (error) {

    }
}

function displayResults(results) {

    resultsContainer.innerHTML = '';
    let searchInputValue = $('#searchInput').val();
    console.log(searchInputValue);
    console.log(results);

    if (searchInputValue) {

        results.forEach(function(result, index) {
            //    console.log(result)
            const resultItem = document.createElement('div');
            resultItem.className = 'result-item';
            resultItem.textContent = result?.customername;
            resultItem.value = result?.id;;
            resultItem.addEventListener('click', function() {
                chooseOption(resultItem);
            });
            resultsContainer.appendChild(resultItem);


        });
    }

    currentSelection = -1;
    updateSelection();

    // Show the results container
    resultsContainer.style.display = results.length > 0 ? 'block' : 'none';
}

function updateSelection() {
    const resultItems = resultsContainer.children;

    for (let i = 0; i < resultItems.length; i++) {
        resultItems[i].classList.toggle('selected', i === currentSelection);
    }
}

async function chooseOption(resultItem) {
    // Handle the chosen option (replace this with your logic)
    //console.log('Chosen:', resultItem.value);
    await existingCustomer(resultItem.value)
    // You may want to clear or update the input field with the chosen option
    searchInput.value = resultItem.textContent;
    // Hide the results container after choosing an option
    resultsContainer.style.display = 'none';
    // alert(resultItem);
}
</script>
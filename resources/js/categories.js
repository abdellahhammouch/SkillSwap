function createCategoryCard(category) {
    const card = document.createElement('div');
    card.className = 'border border-gray-200 rounded-lg p-4';

    const title = document.createElement('h4');
    title.className = 'text-base font-semibold text-gray-900';
    title.textContent = category.name;

    const slug = document.createElement('p');
    slug.className = 'text-sm text-gray-500 mt-1';
    slug.textContent = category.slug;

    const description = document.createElement('p');
    description.className = 'text-sm text-gray-700 mt-2';
    description.textContent = category.description || 'No description';

    card.appendChild(title);
    card.appendChild(slug);
    card.appendChild(description);

    return card;
}

function displayCategories(categoriesList, categories) {
    categoriesList.innerHTML = '';

    categories.forEach(function (category) {
        categoriesList.appendChild(createCategoryCard(category));
    });
}

function displayCategoriesError(categoriesList) {
    categoriesList.innerHTML = '<p class="text-sm text-red-600">Categories could not be loaded.</p>';
}

function loadCategories() {
    const categoriesList = document.getElementById('categories-list');

    if (! categoriesList) {
        return;
    }

    fetch('/categories/data')
        .then(function (response) {
            if (! response.ok) {
                throw new Error('Unable to load categories.');
            }

            return response.json();
        })
        .then(function (categories) {
            displayCategories(categoriesList, categories);
        })
        .catch(function () {
            displayCategoriesError(categoriesList);
        });
}

document.addEventListener('DOMContentLoaded', loadCategories);

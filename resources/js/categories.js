function createCategoryCard(category) {
    const card = document.createElement('div');
    card.className = 'ss-list-card';

    const title = document.createElement('h4');
    title.className = 'text-base font-semibold text-white';
    title.textContent = category.name;

    const slug = document.createElement('p');
    slug.className = 'mt-1 text-sm text-blue-300';
    slug.textContent = category.slug;

    const description = document.createElement('p');
    description.className = 'mt-2 text-sm text-slate-400';
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
    categoriesList.innerHTML = '<p class="text-sm text-rose-300">Categories could not be loaded.</p>';
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

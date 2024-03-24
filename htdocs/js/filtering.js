class Filtering
{
    /**
     * @param {String} rowSelector
     * @param {String} filterInputSelector
     */
    constructor(rowSelector, filterInputSelector)
    {
        this.rowSelector = rowSelector;
        this.filterInputSelector = filterInputSelector;
    }

    Apply()
    {
        this.FilterForTerms(this.GetTerms());
    }

    GetTerms()
    {
        return this.GetInput().value.toLowerCase().match(/\w+/g);
    }

    GetInput()
    {
        return document.querySelector(this.filterInputSelector);
    }

    FilterForTerms(terms)
    {
        console.log(terms);

        const rows = document.querySelectorAll(this.rowSelector);

        rows.forEach((row) =>
        {
            if(this.IsMatch(row.getAttribute('data-filter-text'), terms)) {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        });
    }

    Reset()
    {
        this.GetInput().value = '';
        this.FilterForTerms([]);
    }

    /**
     * @param {String} filter
     * @param {String[]} terms
     * @return {boolean}
     */
    IsMatch(filter, terms)
    {
        if(terms.length === 0) {
            return true;
        }

        let matches = 0;

        for(let i = 0; i < terms.length; i++) {
            if(filter.includes(terms[i])) {
                matches++;
            }
        }

        return matches === terms.length;
    }
}

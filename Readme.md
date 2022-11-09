# TYPO3 Extension `paginate_vh`

This extension just ships a ViewHelper which acts as a dropin replacement for the VieHelper `f:widget.paginate` which has been removed with TYPO3 11.

Thanks to [in2code](https://www.in2code.de/) who created the ViewHelper and added it to [EXT:lux](https://github.com/in2code-de/luxletter).
This extension just ships a simplified version of the ViewHelper.

**Important**: Always try to move the logic to a pagination to the controller and don't put into the view. If you use this extension, use it with care!

## Installation

Install this extension with `composer req georgringer/paginate-vh`.

## Usage

Take a look at the example of the content element "File links" which can be enabled with the following TypoScript:

```typo3_typoscript
lib.contentElement.templateRootPaths.919 = EXT:paginate_vh/Resources/Private/Examples/
```

Different example

```html
<paginate:paginate as="paginatedItems" objects="{items}" itemsPerPage="8">
    <div>
        <f:for each="{paginatedItems.paginator.paginatedItems}" as="item" iteration="fileIterator">
            <div>{item.title}</div>
        </f:for>
    </div>
    <f:if condition="{paginatedItems.paginator.numberOfPages} > 1">
        <ul class="f3-widget-paginator pagination">
            <f:if condition="{paginatedItems.pagination.previousPageNumber} && {pagination.previousPageNumber} >= {pagination.firstPageNumber}">
                <li class="previous">
                    <a href="{f:uri.page(additionalParams:{currentPage:pagination.previousPageNumber})}" title="previous" class="page-link">
                        &lt;
                    </a>
                </li>
            </f:if>
            <f:if condition="{paginatedItems.pagination.hasLessPages}">
                <li class="page-item">…</li>
            </f:if>
            <f:for each="{paginatedItems.pagination.allPageNumbers}" as="page">
                <f:if condition="{page} == {paginator.currentPageNumber}">
                    <f:then>
                        <li class="page-item current active">
                            <span class="page-link">{page}</span>
                        </li>
                    </f:then>
                    <f:else>
                        <li class="page-item">
                            <a href="{f:uri.page(additionalParams:{currentPage:currentPage:page})}" class="page-link">{page}</a>
                        </li>
                    </f:else>
                </f:if>
            </f:for>
            <f:if condition="{paginatedItems.pagination.hasMorePages}">
                <li class="page-item">…</li>
            </f:if>
            <f:if condition="{paginatedItems.pagination.nextPageNumber} && {pagination.nextPageNumber} <= {pagination.lastPageNumber}">
                <li class="next">
                    <a href="{f:uri.page(additionalParams:{currentPage:pagination.nextPageNumber})}" title="next" class="page-link">
                        &gt;
                    </a>
                </li>
            </f:if>
        </ul>
    </f:if>
```

### Routing

```yaml
  Pagination:
    type: Simple
    limitToPages: [5]
    routePath: '/seite/{currentPage}'
    requirements:
        currentPage: '[0-9]{1,3}'
    _arguments:
        currentPage: 'currentPage'
    aspects:
        currentPage:
            type: StaticRangeMapper
            start: '1'
            end: '20'
```

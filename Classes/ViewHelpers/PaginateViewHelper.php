<?php
declare(strict_types=1);

namespace GeorgRinger\PaginateVh\ViewHelpers;

use Closure;
use GeorgRinger\NumberedPagination\NumberedPagination;
use GeorgRinger\PaginateVh\NotPaginatableException;
use TYPO3\CMS\Core\Http\Request;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\PaginationInterface;
use TYPO3\CMS\Core\Pagination\PaginatorInterface;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Service\ExtensionService;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Exception;

/**
 * PaginateViewHelper
 */
class PaginateViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('objects', 'mixed', 'array or queryresult', true);
        $this->registerArgument('as', 'string', 'new variable name', true);
        $this->registerArgument('itemsPerPage', 'int', 'items per page', false, 10);
        $this->registerArgument('numberOfPages', 'int', 'items per page', false, 10);
        $this->registerArgument('argumentName', 'string', 'name for argument', false, 'currentPage');
    }

    public static function renderStatic(array $arguments, Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        if ($arguments['objects'] === null) {
            return $renderChildrenClosure();
        }

        $templateVariableContainer = $renderingContext->getVariableProvider();
        $templateVariableContainer->add($arguments['as'], [
            'pagination' => self::getPagination($arguments, (int)$arguments['numberOfPages']),
            'paginator' => self::getPaginator($arguments),
            'name' => $arguments['as'],
        ]);
        $output = $renderChildrenClosure();
        $templateVariableContainer->remove($arguments['as']);
        return $output;
    }

    protected static function getPagination(array $arguments, int $numberOfPages): PaginationInterface
    {
        $paginator = self::getPaginator($arguments);
        if (class_exists(NumberedPagination::class) && $numberOfPages > 0) {
            return GeneralUtility::makeInstance(NumberedPagination::class, $paginator, $numberOfPages);
        }
        return GeneralUtility::makeInstance(SimplePagination::class, $paginator);
    }

    protected static function getPaginator(array $arguments): PaginatorInterface
    {
        if (is_array($arguments['objects'])) {
            $paginatorClass = ArrayPaginator::class;
        } elseif (is_a($arguments['objects'], QueryResultInterface::class)) {
            $paginatorClass = QueryResultPaginator::class;
        } else {
            throw new Exception('Given object is not supported for pagination', 1634132847);
        }
        return GeneralUtility::makeInstance(
            $paginatorClass,
            $arguments['objects'],
            self::getPageNumber($arguments['argumentName']),
            $arguments['itemsPerPage']
        );
    }

    protected static function getPageNumber(string $argumentName): int
    {
        /** @var Request $request */
        $request = $GLOBALS['TYPO3_REQUEST'];
        $page = (int)($request->getParsedBody()[$argumentName] ?? $request->getQueryParams()[$argumentName] ?? 0);
        return $page > 0 ? $page : 1;
    }
}

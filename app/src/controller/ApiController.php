<?php

namespace Moran\Controller;

require_once('./model/CategoryModel.php');
require_once('./model/ExpenseModel.php');
require_once('./view/ApiView.php');

use Moran\Model\CategoryModel;
use Moran\Model\ExpenseModel;
use Moran\View\ApiView;

class ApiController {

    private ApiView $view;
    private CategoryModel $categoryModel;
    private ExpenseModel $expenseModel;

    public function __construct()
    {
        $this->view = new ApiView;
        $this->categoryModel = new CategoryModel;
        $this->expenseModel = new ExpenseModel;
    }

    public function getExpenses()
    {
        $expenses = (array) $this->expenseModel->getAll();
        $this->view->response($expenses);
    }
}
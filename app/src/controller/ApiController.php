<?php

namespace Moran\Controller;

require_once('./model/CategoryModel.php');
require_once('./model/ExpenseModel.php');
require_once('./view/ApiView.php');

use Moran\Model\CategoryModel;
use Moran\Model\ExpenseModel;
use Moran\View\ApiView;

class ApiController
{

    private ApiView $view;
    private CategoryModel $categoryModel;
    private ExpenseModel $expenseModel;

    public function __construct()
    {
        $this->view = new ApiView;
        $this->categoryModel = new CategoryModel;
        $this->expenseModel = new ExpenseModel;
    }

    public function getExpenses($params = null)
    {
        $expenses = $this->expenseModel->getAll();
        $this->view->response($expenses);
    }

    public function getExpense($params = null)
    {
        $id = $params['pathParams'][':id'];
        $expense = $this->expenseModel->get($id);

        if ($expense) {
            $this->view->response($expense);
        } else {
            $this->view->response("El gasto con el id=$id no existe", 404);
        }
    }

    public function deleteExpense($params)
    {
        $id = $params['pathParams'][':id'];

        if ($this->expenseModel->get($id)) {
            if ($this->expenseModel->delete($id)){
                $this->view->response("El gasto con el id=$id se ha borrado con exito");
            } else {
                $this->view->response("El gasto con el id=$id no se pudo borrar", 500);
            }
        } else {
            $this->view->response("El gasto con el id=$id no existe", 404);
        }
    }
}

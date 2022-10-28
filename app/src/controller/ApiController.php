<?php

namespace Moran\Controller;

require_once('./model/CategoryModel.php');
require_once('./model/ExpenseModel.php');
require_once('./model/hydrator/concrete/ClassMethodsHydrator.php');
require_once('./model/hydrator/strategy/concrete/DateStrategy.php');
require_once('./view/ApiView.php');


use Moran\Model\CategoryModel;
use Moran\Model\DTO\Expense;
use Moran\Model\ExpenseModel;
use Moran\View\ApiView;
use Moran\Model\Hydrator\ClassMethodsHydrator;
use Moran\Model\Hydrator\Strategy\DateStrategy;

class ApiController
{

    private ApiView $view;
    private CategoryModel $categoryModel;
    private ExpenseModel $expenseModel;
    private ClassMethodsHydrator $hydrator;
    private $data;

    public function __construct()
    {
        $this->view = new ApiView();
        $this->categoryModel = new CategoryModel();
        $this->expenseModel = new ExpenseModel();
        $this->hydrator = new ClassMethodsHydrator();
        $this->hydrator->addStrategy('date', new DateStrategy());
        $this->data = file_get_contents("php://input");
    }

    private function getData()
    {
        return json_decode($this->data, true);
    }

    public function getExpenses($params = null)
    {
        $this->getAllEntities($params, $this->expenseModel);
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

    public function deleteExpense($params = null)
    {
        $id = $params['pathParams'][':id'];

        if ($this->expenseModel->get($id)) {
            if ($this->expenseModel->delete($id)) {
                $this->view->response("El gasto con el id=$id se ha borrado con exito");
            } else {
                $this->view->response("El gasto con el id=$id no se pudo borrar", 500);
            }
        } else {
            $this->view->response("El gasto con el id=$id no existe", 404);
        }
    }

    public function addExpense($params = null)
    {
        $expense = $this->hydrator->hydrate($this->getData(), new Expense());

        if ($expense->isFilled()) {
            $expense->setId($this->expenseModel->add($expense));
            $this->view->response($expense, 201);
        } else {
            $this->view->response("Complete los datos", 404);
        }
    }

    private function camelCaseToPascalCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    private function getAllEntities($params, $model)
    {
        $sortBy = $params['queryParams']['sortBy'];
        $order = $params['queryParams']['order'];

        if (isset($sortBy)) {
            if ($model->validField($sortBy)) {
                if (isset($order) && !in_array(strtolower($order), ['asc', 'desc'])) {
                    $this->view->response("El order debe ser 'ASC' o 'DESC' (mayusculas o minusculas).", 400);
                    return;
                }
                if (isset($order))
                    $this->view->response($model->getAll($this->camelCaseToPascalCase($sortBy), strtoupper($order)));
                else
                    $this->view->response($model->getAll($this->camelCaseToPascalCase($sortBy)));
            } else {
                $this->view->response("El campo $sortBy no existe.", 400);
            }
        } else {
            $this->view->response($model->getAll());
        }
    }
}

<?php

namespace Moran\Model;

require_once('./model/dto/Expense.php');
require_once('./model/hydrator/concrete/ClassMethodsHydrator.php');
require_once('./model/hydrator/strategy/concrete/DateStrategy.php');

use \PDO;
use \PDOException;
use Moran\Model\DTO\Expense;
use Moran\Model\Hydrator\ClassMethodsHydrator;
use Moran\Model\Hydrator\Strategy\DateStrategy;

class ExpenseModel {

    private $db;
    private $hydrator;

    function __construct()
    {
        $this->db = new PDO('mysql:host=db;port=3306;dbname=app;charset=utf8', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->exec("set names 'utf8'");

        $this->hydrator = new ClassMethodsHydrator();
        $this->hydrator->addStrategy('date', new DateStrategy());
    }

    function getAllByCategory($categoryId)
    {
        $query = $this->db->prepare('SELECT * FROM expense WHERE category_id = ?;');
        $query->execute(array($categoryId));
        $expenseData = $query->fetchAll(PDO::FETCH_ASSOC);

        $result = array();
        foreach($expenseData as $expense) {
            $result[] = $this->hydrator->hydrate($expense, new Expense());
        }

        return $result;
    }

    function getAll()
    {
        $query = $this->db->prepare('SELECT * FROM expense;');
        $query->execute();
        $expenseData = $query->fetchAll(PDO::FETCH_ASSOC);

        $result = array();
        foreach($expenseData as $expense) {
            $result[] = $this->hydrator->hydrate($expense, new Expense());
        }

        return $result;
    }

    function get($expenseId)
    {
        $query = $this->db->prepare('SELECT * FROM expense WHERE id = ?;');
        $query->execute(array($expenseId));
        $expenseData = $query->fetch(PDO::FETCH_ASSOC);

        $expense = null;
        if($query->rowCount() > 0)
            $expense = $this->hydrator->hydrate($expenseData, new Expense());

        return $expense;
    }

    function add(Expense $expense)
    {
        $query = $this->db->prepare("INSERT INTO expense (id, date, product_name, cost, category_id) VALUES (NULL, ?, ?, ?, ?);");
        $arr = array($expense->getDate()->format('Y-m-d'), $expense->getProductName(), $expense->getCost(), $expense->getCategoryId());
        $query->execute($arr);
    }

    function delete(int $expenseId): bool
    {
        try {
            $query = $this->db->prepare("DELETE FROM expense WHERE expense.id = ?");
            $query->execute(array($expenseId));
        } catch (PDOException $e) {
            return false;
        }
        return true;        
    }

    function update(Expense $expense)
    {
        $query = $this->db->prepare("UPDATE expense SET date = ?, product_name = ?, cost = ?, category_id = ? WHERE expense.id = ?;");
        $query->execute(array($expense->getDate()->format('Y-m-d'), $expense->getProductName(), $expense->getCost(), $expense->getCategoryId(), $expense->getId()));
    }
}
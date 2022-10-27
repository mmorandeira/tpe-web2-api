<?php

namespace Moran\Model;

use \PDO;
use \PDOException;
use Moran\Model\DTO\Category;
use Moran\Model\Hydrator\ClassMethodsHydrator;

class CategoryModel
{
    
    private $db;
    private $hydrator;

    function __construct()
    {
        $this->db = new PDO('mysql:host=db;port=3306;dbname=app;charset=utf8', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->exec("set names 'utf8'");

        $this->hydrator = new ClassMethodsHydrator();
    }

    function getAll()
    {
        $query = $this->db->prepare('SELECT * FROM category;');
        $query->execute();
        $expenseData = $query->fetchAll(PDO::FETCH_ASSOC);

        $result = array();
        foreach($expenseData as $expense) {
            $result[] = $this->hydrator->hydrate($expense, new Category());
        }

        return $result;
    }

    function get($categoryId)
    {
        $query = $this->db->prepare('SELECT * FROM category WHERE id = ?;');
        $query->execute(array($categoryId));
        $categoryData = $query->fetch(PDO::FETCH_ASSOC);

        return $this->hydrator->hydrate($categoryData, new Category());
    }

    function exist($categoryId)
    {
        $query = $this->db->prepare('SELECT * FROM category WHERE id = ?;');
        $query->execute(array($categoryId));
        $categoryData = $query->fetch(PDO::FETCH_ASSOC);

        return $query->rowCount() > 0;
    }

    function add(Category $category)
    {
        $query = $this->db->prepare("INSERT INTO category (id, name, description, color) VALUES (NULL, ?, ?, ?);");
        $query->execute(array($category->getName(), $category->getDescription(), $category->getColor()));
    }

    function delete(int $categoryId): bool
    {
        try {
            $query = $this->db->prepare("DELETE FROM category WHERE category.id = ?");
            $query->execute(array($categoryId));
        } catch (PDOException $e) {
            return false;
        }
        return true;    
    }

    function update(Category $category)
    {
        $query = $this->db->prepare("UPDATE category SET name = ?, description = ?, color = ? WHERE category.id = ?;");
        $query->execute(array($category->getName(), $category->getDescription(), $category->getColor(), $category->getId()));
    }
}
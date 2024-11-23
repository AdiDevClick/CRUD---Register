<?php

class PermissionChecker
{
    private $pdo;
    private $cache;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->cache = [];
    }

    private function getUserAttributes($userId)
    {
        $stmt = $this->pdo->prepare("SELECT attribute_name, attribute_value FROM user_attributes WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $attributes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $attributes[$row['attribute_name']] = $row['attribute_value'];
        }
        return $attributes;
    }

    private function getRulesForAction($action)
    {
        $stmt = $this->pdo->prepare("SELECT condition FROM rules WHERE description LIKE :action");
        $stmt->execute(['action' => "%" . $action . "%"]);
        $rules = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rules[] = json_decode($row['condition'], true);
        }
        return $rules;
    }

    private function evaluateCondition($condition, $attributes)
    {
        foreach ($condition as $key => $value) {
            if (!isset($attributes[$key]) || $attributes[$key] != $value) {
                return false;
            }
        }
        return true;
    }

    public function checkPermission($userId, $action)
    {
        $cacheKey = $userId . "_" . $action;

        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $userAttributes = $this->getUserAttributes($userId);
        $rules = $this->getRulesForAction($action);

        foreach ($rules as $condition) {
            if ($this->evaluateCondition($condition, $userAttributes)) {
                $this->cache[$cacheKey] = true;
                return true;
            }
        }

        $this->cache[$cacheKey] = false;
        return false;
    }
}

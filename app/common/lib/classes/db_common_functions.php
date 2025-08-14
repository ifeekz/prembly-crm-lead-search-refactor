<?php

declare(strict_types=1);

namespace App\DB;

use \PDO;

/**
 * DB_Common_Functions class provides common database functions for lead searches,
 * storing search criteria, and retrieving leads based on various fields.
 */
class DB_Common_Functions
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Store data into a table
     */
    public function store_data(array $data, array $fields, string $tableName): bool
    {
        $fieldList = implode(", ", $fields);
        $placeholders = implode(", ", array_fill(0, count($fields), "?"));
        $sql = "INSERT INTO {$tableName} ({$fieldList}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function countLeadsBy(string $field, string $text, int $ownerId): int
    {
        $sql = "SELECT COUNT(*) FROM leads WHERE :field LIKE :txt AND owner_id = :owner_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['field' => $field, 'txt' => "%$text%", 'owner_id' => $ownerId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get human-readable search criteria text
     */
    public function getSearchCriteriaText(string $searchBy): string
    {
        $map = [
            'fname'        => 'First Name',
            'lname'        => 'Last Name',
            'phone_number' => 'Phone Number',
            'email'        => 'Email',
            'crm_id'       => 'CRM ID',
            'mkt_id'       => 'Marketing ID',
            'company_name' => 'Company Name'
        ];
        return $map[$searchBy] ?? 'First Name';
    }

    /**
     * Search helpers — each returns an array of lead objects
     */
    public function searchLeadsByFirstName(string $text, int $ownerId): array
    {
        return $this->searchLeads("fname", $text, $ownerId);
    }

    public function searchLeadsByLastName(string $text, int $ownerId): array
    {
        return $this->searchLeads("lname", $text, $ownerId);
    }

    public function searchLeadsByPhone(string $text, int $ownerId): array
    {
        return $this->searchLeads("phone_number", $text, $ownerId);
    }

    public function searchLeadsByEmail(string $text, int $ownerId): array
    {
        return $this->searchLeads("email", $text, $ownerId);
    }

    public function searchLeadsByCRMId(string $text, int $ownerId): array
    {
        return $this->searchLeads("crm_id", $text, $ownerId);
    }

    public function searchLeadsByMktId(string $text, int $ownerId): array
    {
        return $this->searchLeads("mkt_id", $text, $ownerId);
    }

    public function searchLeadsByCompanyName(string $text, int $ownerId): array
    {
        return $this->searchLeads("company_name", $text, $ownerId);
    }

    /**
     * Search with offset (pagination)
     */
    public function searchLeadsByFirstNameWithOffset(string $text, int $ownerId, int $offset): array
    {
        return $this->searchLeads("fname", $text, $ownerId, $offset);
    }

    public function searchLeadsByLastNameWithOffset(string $text, int $ownerId, int $offset): array
    {
        return $this->searchLeads("lname", $text, $ownerId, $offset);
    }

    public function searchLeadsByPhoneWithOffset(string $text, int $ownerId, int $offset): array
    {
        return $this->searchLeads("phone_number", $text, $ownerId, $offset);
    }

    public function searchLeadsByEmailWithOffset(string $text, int $ownerId, int $offset): array
    {
        return $this->searchLeads("email", $text, $ownerId, $offset);
    }

    public function searchLeadsByCompanyNameWithOffset(string $text, int $ownerId, int $offset): array
    {
        return $this->searchLeads("company_name", $text, $ownerId, $offset);
    }

    /**
     * Generic search method
     */
    private function searchLeads(string $field, string $text, int $ownerId, int $offset = 0): array
    {
        $limit = defined('PAGE_SIZE') ? PAGE_SIZE : 20;
        $sql = "SELECT * FROM leads 
                WHERE {$field} LIKE :search 
                AND owner_id = :ownerId
                ORDER BY real_date DESC";

        if ($offset >= 0) {
            $sql .= " LIMIT :offset, :limit";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search', "%{$text}%", PDO::PARAM_STR);
        $stmt->bindValue(':ownerId', $ownerId, PDO::PARAM_INT);

        if ($offset >= 0) {
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

<?php
declare(strict_types=1);

namespace App\Service;

use App\DB\DB_Common_Functions;
use App\Utils\Common_Utilities;

class LeadSearchService
{
    private DB_Common_Functions $db;
    private Common_Utilities $util;
    private int $pageSize;

    public function __construct(DB_Common_Functions $db, Common_Utilities $util, int $pageSize = PAGE_SIZE)
    {
        $this->db = $db;
        $this->util = $util;
        $this->pageSize = $pageSize;
    }

    public function search(array $request, int $ownerId, int $agentId): array
    {
        $searchBy   = $request['searchValue'] ?? 'fname';
        $searchText = $request['searchText'] ?? '';
        $searchText = $this->util->sanitize_html(trim((string) $searchText));

        if (!empty($request['searchBtn'])) {
            $tableName = "log_agent_searches";
            $criteriaText = $this->db->getSearchCriteriaText($searchBy);
            $data = [$agentId, $searchText, $criteriaText];
            $fields = ['agent_id', 'search_value', 'search_criteria'];
            $this->db->store_data($data, $fields, $tableName);
        }

        $currentPage = isset($request['current_page']) ? max(1, (int) $request['current_page']) : 1;

        $totalCount = $this->getTotalCount($searchBy, $searchText, $ownerId);
        $pagenation = $this->util->construct_pagination($currentPage, $totalCount, $this->pageSize);
        // $totalPages = (int) ceil($totalCount / $this->pageSize);

        // if ($currentPage > $totalPages && $totalPages > 0) {
        //     $currentPage = $totalPages;
        // }

        $offset = ($currentPage - 1) * $this->pageSize;
        $leads  = $this->getLeads($searchBy, $searchText, $ownerId, $offset);

        return [
            'rows'       => $leads,
            'pagenation'  => $pagenation,
            'searchBy'    => $searchBy,
            'searchText'  => $searchText
        ];
    }

    private function getTotalCount(string $searchBy, string $searchText, int $ownerId): int
    {
        if ($searchText === '') {
            return 0;
        }
        $method = match ($searchBy) {
            'fname'        => 'searchLeadsByFirstName',
            'lname'        => 'searchLeadsByLastName',
            'phone_number' => function($txt, $id) { return $this->db->searchLeadsByPhone($this->util->formatPhoneNumber($txt), $id); },
            'email'        => function($txt, $id) { return $this->db->searchLeadsByEmail(urldecode($txt), $id); },
            'crm_id'       => 'searchLeadsByCRMId',
            'mkt_id'       => 'searchLeadsByMktId',
            default        => 'searchLeadsByFirstName'
        };

        $results = is_string($method)
            ? $this->db->$method($searchText, $ownerId)
            : $method($searchText, $ownerId);

        return count($results);
    }

    private function getLeads(string $searchBy, string $searchText, int $ownerId, int $offset): array
    {
        if ($searchText === '') {
            return [];
        }
        $method = match ($searchBy) {
            'fname'        => 'searchLeadsByFirstNameWithOffset',
            'lname'        => 'searchLeadsByLastNameWithOffset',
            'phone_number' => function($txt, $id, $off) { return $this->db->searchLeadsByPhoneWithOffset($this->util->formatPhoneNumber($txt), $id, $off); },
            'email'        => function($txt, $id, $off) { return $this->db->searchLeadsByEmailWithOffset(urldecode($txt), $id, $off); },
            'crm_id'       => 'searchLeadsByCRMId',
            'mkt_id'       => 'searchLeadsByMktId',
            default        => 'searchLeadsByFirstNameWithOffset'
        };

        return is_string($method)
            ? $this->db->$method($searchText, $ownerId, $offset)
            : $method($searchText, $ownerId, $offset);
    }
}

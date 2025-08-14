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
        $offset = ($currentPage - 1) * $this->pageSize;
        $leads  = $this->getLeads($searchBy, $searchText, $ownerId, $offset);

        return [
            'rows'       => $leads,
            'pagenation'  => $pagenation,
        ];
    }

    private function getTotalCount(string $searchBy, string $searchText, int $ownerId): int
    {
        return $this->db->countLeadsBy($searchBy, $searchText, $ownerId);
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
            'company_name' => 'searchLeadsByCompanyName',
            default        => 'searchLeadsByFirstNameWithOffset'
        };

        return is_string($method)
            ? $this->db->$method($searchText, $ownerId, $offset)
            : $method($searchText, $ownerId, $offset);
    }
}

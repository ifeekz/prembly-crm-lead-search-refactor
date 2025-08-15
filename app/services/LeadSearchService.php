<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Lead;
use Prembly\Crm\DB\DB_Common_Functions;
use Prembly\Crm\Utils\Common_Utilities;

class LeadSearchService
{
    private Lead $model;
    private Common_Utilities $util;
    private int $pageSize;

    public function __construct(Lead $model, Common_Utilities $util, int $pageSize = PAGE_SIZE)
    {
        $this->model = $model;
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
            $criteriaText = $this->model->getSearchCriteriaText($searchBy);
            $data = [$agentId, $searchText, $criteriaText];
            $fields = ['agent_id', 'search_value', 'search_criteria'];
            $this->model->store_data($data, $fields, $tableName);
        }

        $currentPage = isset($request['current_page']) ? max(1, (int) $request['current_page']) : 1;

        $totalCount = $this->getTotalCount($searchBy, $searchText, $ownerId);
        $pagination = $this->util->construct_pagination($currentPage, $totalCount, $this->pageSize);
        $offset = ($currentPage - 1) * $this->pageSize;
        $leads  = $this->getLeads($searchBy, $searchText, $ownerId, $offset);

        return [
            'rows'       => $leads,
            'pagination'  => $pagination,
        ];
    }

    private function getTotalCount(string $searchBy, string $searchText, int $ownerId): int
    {
        return $this->model->countLeadsBy($searchBy, $searchText, $ownerId);
    }

    private function getLeads(string $searchBy, string $searchText, int $ownerId, int $offset): array
    {
        if ($searchText === '') {
            return [];
        }
        $method = match ($searchBy) {
            'fname'        => 'searchLeadsByFirstNameWithOffset',
            'lname'        => 'searchLeadsByLastNameWithOffset',
            'phone_number' => function($txt, $id, $off) { return $this->model->searchLeadsByPhoneWithOffset($this->util->formatPhoneNumber($txt), $id, $off); },
            'email'        => function($txt, $id, $off) { return $this->model->searchLeadsByEmailWithOffset(urldecode($txt), $id, $off); },
            'crm_id'       => 'searchLeadsByCRMId',
            'mkt_id'       => 'searchLeadsByMktId',
            'company_name' => 'searchLeadsByCompanyName',
            default        => 'searchLeadsByFirstNameWithOffset'
        };

        return is_string($method)
            ? $this->model->$method($searchText, $ownerId, $offset)
            : $method($searchText, $ownerId, $offset);
    }
}

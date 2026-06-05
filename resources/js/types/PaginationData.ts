import { Employee } from "./employee";
export interface PaginatedData {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    first_page_url: string;
    last_page_url: string;
    next_page_url: string;
    prev_page_url: string;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    data: Employee
}
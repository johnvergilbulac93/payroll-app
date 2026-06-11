export interface Role {
    public_id: string ;
    Description: string;
    isActive?: boolean;
    created_at?: string;
    deleted_at?: string;
    updated_at?: string;
}
export interface RoleError {
    Description: string;
}
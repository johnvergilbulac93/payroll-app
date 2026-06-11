export interface User {
    id?: any;
    public_id: string;
    name: string;
    email?: string;
    avatar? : string;
    username?: string;
    isActive: boolean;
    role: string | number;
    created_at?: string;
    deleted_at?: string;
}
export interface UserError {
    name: string;
    email: string;
    password: string;
    role: string
    username: string
}
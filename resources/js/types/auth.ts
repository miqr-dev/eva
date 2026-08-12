export type User = {
    id: number;
    organization_unit_id: number | null;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User | null;
};

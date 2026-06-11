export interface Permission {
    id: number
    name: string
    slug?: string
    checked?: boolean
    children?: Permission[]
}
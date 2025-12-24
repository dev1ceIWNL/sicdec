export interface User {
    id_usuario: number;
    nombre: string;
    correo: string;
    rol: 'admin' | 'usuario';
    estatus: number;
}

export interface Auth {
    user: User;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: Auth;
    errors: Record<string, string>;
};

// Props específicos de páginas
export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = PageProps<T> & {
    name?: string;
    quote?: { message: string; author: string };
};
import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { PageProps as AppPageProps } from './';
import type { Config, RouteParam } from "ziggy-js";

declare global {
    interface Window {
        axios: AxiosInstance
    };
    function route(
        name: string,
        params?: RouteParam,
        absolute?: boolean,
        config?: Config
    ): string;
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps { }
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

export async function plannerRequest(url, { method = 'GET', body } = {}) {
    const options = {
        method,
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
    };

    if (body !== undefined) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(body);
    }

    const response = await fetch(url, options);
    const payload = await response.json().catch(() => ({}));

    if (!response.ok) {
        const error = new Error(payload.message || 'Permintaan gagal.');
        error.status = response.status;
        error.errors = payload.errors ?? {};
        throw error;
    }

    return payload;
}

export const plannerApi = {
    get: (url) => plannerRequest(url),
    post: (url, body) => plannerRequest(url, { method: 'POST', body }),
    patch: (url, body) => plannerRequest(url, { method: 'PATCH', body }),
    delete: (url) => plannerRequest(url, { method: 'DELETE' }),
};

export class Cookie {
    public static setCookie(name: string, value: string, days: number) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    public static getCookie(name: string): string | null {
        const cookieName = name + "=";
        const decodedCookie = decodeURIComponent(document.cookie);
        const cookie = decodedCookie.split(";");
        cookie.forEach(element => {
                let c: string = element;
                while (c.charAt(0) === " ") {
                    c = c.substring(1);
                }
                if (c.indexOf(cookieName) === 0) {
                    return c.substring(cookieName.length, c.length);
                }
            }
        );

        return null;
    }

    public static deleteCookie(name: string) {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }
}
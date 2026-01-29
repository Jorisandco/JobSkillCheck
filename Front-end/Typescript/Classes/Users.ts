import { APICals } from "./API";
import { Cookie } from "./Cookie";

export class Users {
    private sessionToken: string;

    constructor(sessionToken: string) {
        this.sessionToken = sessionToken;
    }
}
class Users {
    private Email: string;

    constructor(email: string) {
        this.Email = email;
    }

    public HasAnsweredQuestion(): boolean {
        if (this.MadeQuestion())
            return true;



    }

    private MadeQuestion(): boolean {
        return true;
    }
}
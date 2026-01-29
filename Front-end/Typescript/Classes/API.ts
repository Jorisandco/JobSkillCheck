import axios from "axios";
import dotenv from "dotenv";

export class APICals {
    private axiosInstance;

    public constructor() {
        dotenv.config();

        const baseURL = process.env.APIURL;
        if (!process.env.APIURL) {
            console.warn("Warning: APIURL is not set in the .env file. Using default base URL.");
        }

        this.axiosInstance = axios.create({
            baseURL: baseURL || "http://localhost:3000/api",
            headers: {
                "Content-Type": "application/json",
            },
        });
    }

    public async post(endpoint: string, data: Record<string, any> = {}): Promise<any> {
        try {
            const response = await this.axiosInstance.post(endpoint, data);
            return response.data;
        } catch (error) {
            console.error("POST request error:", error);
            throw error;
        }
    }
}
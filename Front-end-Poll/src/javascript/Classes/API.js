import axios from "axios";

export class APICals {
    axiosInstance;

     constructor() {
        this.axiosInstance = axios.create({
            baseURL: "http://back-end-poll.local/api",
            headers: {
                "Content-Type": "application/json",
            },
        });
    }

     async post(endpoint, data){
        try {
            const response = await this.axiosInstance.post(endpoint, data);
            return response.data;
        } catch (error) {
            console.error("POST request error:", error);
            throw error;
        }
    }
}
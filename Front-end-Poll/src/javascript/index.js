import {Users} from "./Classes/Users.js";
import {APICals} from "./Classes/API.js";
import {Cookie} from "./Classes/Cookie.js";

const user = new Users(Cookie.getCookie("Session") || "");

const loggedIn = await user.IsUserLoggedIn()
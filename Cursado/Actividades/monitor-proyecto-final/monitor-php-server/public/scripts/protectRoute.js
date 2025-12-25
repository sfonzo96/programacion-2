import { auth } from "./util.js";

if (auth.isJWTexpired()) {
	auth.logout();
}
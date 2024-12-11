//<Citation>: ChatGPT helped with the debugging of the code -(CHATGPT)
// Middleware to check if the user is authenticated
const authenticateUser = (req, res, next) => {
        // Allow unauthenticated access to login route
        if (req.path === '/login') {
            return next();
        }

    if (!req.session || !req.session.email) {
        // If the user is not logged in, redirect to the login page
        return res.redirect('/adminAndManager/login.html');
    }
    // If the user is authenticated, proceed to the next middleware or route handler
    next();
};

module.exports = authenticateUser;

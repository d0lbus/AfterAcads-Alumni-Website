const express = require('express');
const cors = require('cors');

const app = express();
const port = 3000;

// Enable CORS
app.use(cors());

// Import routes
const userRoutes = require('./routes/users');
const postRoutes = require('./routes/posts');
const eventRoutes = require('./routes/events');

// Mount routes
app.use('/api/users', userRoutes);
app.use('/api/posts', postRoutes);
app.use('/api/events', eventRoutes);

// Start the server
app.listen(port, () => {
  console.log(`Node.js server is running on port ${port}`);
});

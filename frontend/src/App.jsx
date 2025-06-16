import {
    BrowserRouter as Router,
    Routes,
    Route,
    Navigate,
} from "react-router-dom";
import Login from "./pages/Login";
import Register from "./pages/Register";
import ForgotPassword from "./pages/ForgotPassword";
import Dashboard from "./pages/Dashboard";
import ProfileForm from "./pages/ProfileForm";
import ProtectedRoute from "./components/ProtectedRoutes";
import ResetPassword from "./pages/ResetPassword";
import UserListScreen from "./pages/UserListScreen";
import WorkHoursDashboard from "./pages/WorkHoursDashboard";
import VacationDashboard from "./pages/VacationDashboard";
import ConfirmEmailSuccess from "./pages/ConfirmEmailSuccess";
import UserUpdateScreen from "./pages/UserUpdateScreen";

function App() {
    return (
        <Router>
            <Routes>
                <Route path="/login" element={<Login />} />
                <Route path="/register" element={<Register />} />
                <Route path="/forgot-password" element={<ForgotPassword />} />
                <Route
                    path="/reset-password/:token"
                    element={<ResetPassword />}
                />
                <Route
                    path="/confirm-email/:token"
                    element={<ConfirmEmailSuccess />}
                />
                <Route element={<ProtectedRoute />}>
                    <Route path="/dashboard" element={<Dashboard />} />
                    <Route path="/profile" element={<ProfileForm />} />
                    <Route path="/users" element={<UserListScreen />} />
                    <Route
                        path="/enter-hours"
                        element={<WorkHoursDashboard />}
                    />
                    <Route path="/requests" element={<VacationDashboard />} />
                    {/* <Route path="/users/create" element={<UserCreateScreen />} /> New user */}
                    <Route
                        path="/admin/user/update/:id"
                        element={<UserUpdateScreen />}
                    />
                </Route>
                <Route
                    path="*"
                    element={<Navigate to="/dashboard" replace />}
                />
            </Routes>
        </Router>
    );
}

export default App;

import { motion } from "motion/react";
import { useNavigate } from '@tanstack/react-router';

export default function NavLink({to, children}: {to: string, children: string}) {
    const navigate = useNavigate();

    function handleNavigate() {
        if(to) navigate({to});
    }

    return (
        <motion.button
            onClick={handleNavigate}
            className="nav-link"
        >{children}</motion.button>
    );
}
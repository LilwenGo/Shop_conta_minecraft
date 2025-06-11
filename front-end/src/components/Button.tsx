import { useNavigate } from '@tanstack/react-router';
import { motion } from 'motion/react';

export default function Button({to, onClick, variant = "primary", className = "", children}: {to?: string | undefined, onClick?: any, variant?: string, className?: string, children: React.ReactNode | string}) {
    const navigate = useNavigate();
    const variants = new Set([
        "primary",
        "accent",
        "danger"
    ]);

    function handleNavigate() {
        if(to) navigate({to});
    }

    if(!variants.has(variant)) {
        variant = 'primary';
    }

    return (
        <motion.button 
            whileHover={{scale: 1.05, transition: {duration: 0.15}}}
            whileTap={{scale: 0.95, transition: {duration: 0.05}}}
            onClick={onClick || handleNavigate}
            className={`btn btn-${variant} ${className}`}
        >{children}</motion.button>
    );
}
import { motion } from "motion/react";
import type React from "react";

export default function Card({children, onClick}: {children: React.ReactNode, onClick?: React.MouseEventHandler}) {
    return (
        <motion.article
            onClick={onClick ?? (() => {})}
            whileHover={{scale: 1.05, transition: {duration: 0.15}}}
            className="card"
        >{children}</motion.article>
    );
}